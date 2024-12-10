import {defineStore} from 'pinia';
import {reactive} from 'vue';
import axios from 'axios';
import Record from "../../../utils/Record.js";
import RequestQueue from "../../../utils/RequestQueue.js";
import {useListStore} from "./ListStore.js";
import {useStateStore} from "./StateStore.js";

export const useRecordStore = defineStore('RecordStore', () => {
    const data = reactive({
        speaker: {
            id: '',
            name: '',
            avatar: '',
            user_id: null,
            dialect_id: null,
            location_id: null,
            fluency: '',
            gender: '',
            exists: false,
        },
        queue: {
            type: '',
            name: null,
        },
        decks: [],
        pronunciations: [],
        records: {},
        status: {},
        errors: {},
        statusCount: {
            up: 0,
            ready: 0,
            stashing: 0,
            stashed: 0,
            uploading: 0,
            uploaded: 0,
            finalizing: 0,
            done: 0,
            error: 0,
        },
    });

    const StateStore = useStateStore();
    const ListStore = useListStore();
    const requestQueue = new RequestQueue();

    const fetchSpeaker = async () => {
        if (!data.speaker.user_id) {
            try {
                const response = await axios.get('/record/speaker');
                if (response.data) {
                    data.speaker = response.data.speaker;
                    data.speaker.name = response.data.name;
                    data.speaker.avatar = response.data.avatar;
                    data.speaker.exists = response.data.exists;
                }
            } catch (error) {
                console.error('Error loading speaker:', error);
            }
        }
    };

    const saveSpeaker = async () => {
        try {
            const response = await axios.post('/record/speaker', {
                user_id: data.speaker.user_id,
                dialect_id: data.speaker.dialect_id,
                location_id: data.speaker.location_id,
                fluency: data.speaker.fluency,
                gender: data.speaker.gender,
            });

            alert(response.data.message);
            data.speaker = response.data.speaker;
            data.speaker.name = response.data.name;
            data.speaker.avatar = response.data.avatar;
            data.speaker.exists = true;
            return true;

        } catch (error) {
            console.error('Error saving speaker:', error);
            return false;
        }
    };

    const setStatus = (pronunciation, status) => {
        if (!data.status[pronunciation]) {
            data.status[pronunciation] = status;
        } else {
            data.statusCount[data.status[pronunciation]]--;
            data.status[pronunciation] = status;
        }
        data.statusCount[status]++;
    };

    const setError = (pronunciation, error) => {
        if (!data.errors[pronunciation]) {
            data.errors[pronunciation] = error;
        } else {
            if (data.errors[pronunciation] !== false) {
                data.statusCount.error--;
            }
            data.errors[pronunciation] = error;
        }

        if (error !== false) {
            data.statusCount.error++;
        }
    };

    const fetchAuto = async () => {
        try {
            const response = await axios.post('/record/pronunciations', {
                speaker_id: data.speaker.id,
                dialect_id: data.speaker.dialect_id,
                listedPronunciations: data.pronunciations,
            });

            if (response.data) {
                response.data.pronunciations.forEach((pronunciation) => {
                    const exists = data.pronunciations.some(p => p.id === pronunciation.id);
                    if (!exists) {
                        data.pronunciations.push(pronunciation);
                    }
                });

                data.queue.name = 'Auto Queue';
            }

        } catch (error) {
            console.error('Error loading pronunciations:', error);
        }
    };

    const removePronunciation = async (pronunciation) => {
        const index = data.pronunciations.indexOf(pronunciation);

        if (data.records[pronunciation.id]) {
            const confirmed = confirm('You have a stashed recording for this item. Are you sure you would like to remove the item from your Queue before uploading? Your stashed recording will be deleted.');
            if (!confirmed) return false;

            const discarded = await discardRecord(pronunciation.id);
            if (!discarded) return false;
        }

        data.pronunciations.splice(index, 1);
        return true;
    };

    const fetchSavedDecks = async () => {
        try {
            const response = await axios.get('/record/decks');
            if (response.data && response.data.decks) {
                data.decks = response.data.decks;
            }
        } catch (error) {
            console.error('Error fetching saved decks:', error);
        }
    };

    const fetchDeck = async (deckId) => {
        try {
            const response = await axios.get(`/record/decks/${deckId}`);
            if (response.data) {
                response.data.pronunciations.forEach((pronunciation) => {
                    const exists = data.pronunciations.some(p => p.id === pronunciation.id);
                    if (!exists) {
                        data.pronunciations.push(pronunciation);
                    }
                });

                data.queue = {
                    type: 'deck',
                    name: response.data.deck.name,
                };
            }
        } catch (error) {
            console.error(`Error fetching deck with ID ${deckId}:`, error);
        }
    };

    const flushQueue = async () => {
        if (data.pronunciations.length === 0 || confirm('Doing this will clear your Queue. Proceed?')) {
            data.records = {};
            data.status = {};
            data.errors = {};
            data.pronunciations = [];

            data.queue = {
                type: '',
                name: null,
            };

            return true;

        } else {
            return false;
        }
    }

    const stashRecord = async (pronunciation, blob) => {
        if (!data.records[pronunciation.id]) {
            data.records[pronunciation.id] = new Record(pronunciation);
            data.records[pronunciation.id].setSpeaker(data.speaker);
        }

        const record = data.records[pronunciation.id];

        setStatus(pronunciation.id, 'ready');
        setError(pronunciation.id, false);

        if (blob) {
            try {
                await record.setBlob(blob);

            } catch (error) {
                setError(pronunciation.id, 'Audio record initialization failed.');
                console.error(error);
                throw new Error('Audio record initialization failed.');
            }
        }

        setStatus(pronunciation.id, 'stashing');

        try {
            await requestQueue.push(async () => {
                await record.stashRecord();
                data.records[pronunciation.id].url = record.url;

                setStatus(pronunciation.id, 'stashed');
            });

        } catch (error) {
            setError(pronunciation.id, error.message || 'Stash upload failed.');
            console.error(`Error stashing pronunciation ${pronunciation.translit}:`, error);
            throw error;
        }
    };

    const playRecord = () => {
        const id = data.pronunciations[ListStore.selected].id;

        if (data.status[id] === 'stashed') {
            const record = data.records[id];

            if (record?.url) {
                const audio = new Audio(record.url);
                audio.play();
            }
        }
    };

    const discardRecord = async (id) => {
        const confirmed = confirm('Are you sure you want to discard this recording?');
        if (!confirmed) return false;

        const record = data.records[id];
        if (!record) {
            console.warn(`Record with ID ${id} not found.`);
            return false;
        }

        const stashkey = record.stashkey;
        if (!stashkey) {
            console.error(`No stashkey found for Record with ID ${id}.`);
            return false;
        }

        try {
            const response = await axios.delete(`/api/record/discard/${stashkey}`);

            if (response.status === 200) {
                console.log(`Recording with stashkey ${stashkey} discarded successfully.`);

            } else {
                console.error(`Failed to discard recording with stashkey ${stashkey}.`);
                return false;
            }

            delete data.records[id];
            delete data.status[id];
            delete data.errors[id];
            data.statusCount.stashed--;
            data.errors[id] && data.statusCount.error--;
            return true;

            // const recordIds = Object.keys(data.records);
            //
            // if (recordIds.length > 0) {
            //     const currentIndex = recordIds.indexOf(String(id));
            //     const nextIndex = (currentIndex + 1) < recordIds.length ? currentIndex + 1 : 0;
            //     const nextRecordId = recordIds[nextIndex];
            //     const nextPronunciationIndex = data.pronunciations.findIndex(p => p.id === Number(nextRecordId));
            //     ListStore.selectWord(nextPronunciationIndex);
            //
            // } else {
            //     console.log("No more records available to select.");
            // }

        } catch (error) {
            console.error(`Error discarding recording with stashkey ${stashkey}:`, error);
            return false;
        }
    };

    const clearStash = () => {
        fetch(`/api/record/clear/${data.speaker.id}`, {method: 'DELETE'})
            .then(response => {
                if (response.ok) {
                    console.log('Stash directory cleaned up successfully.');

                } else {
                    console.error('Failed to clean up stash directory.');
                }

            })
            .catch(error => console.error('Error during stash cleanup:', error));
    };

    // TODO: Stop recording before uploading.
    const uploadRecords = async () => {
        if (confirm('Are you sure you want to upload all of your recordings?')) {
            StateStore.data.isUploading = true;

            const stashedIds = Object.keys(data.status).filter(
                (id) => data.status[id] === 'stashed'
            );

            for (const id of stashedIds) {
                try {
                    const pronunciationIndex = data.pronunciations.findIndex(
                        (p) => p.id === Number(id)
                    );

                    if (pronunciationIndex !== -1) {
                        ListStore.selectWord(pronunciationIndex);
                    }

                    setStatus(id, 'uploading');

                    const record = data.records[id];
                    if (!record) {
                        throw new Error(`Record with ID ${id} not found.`);
                    }

                    await record.uploadRecord();

                    setStatus(id, 'uploaded');

                    if (pronunciationIndex !== -1) {
                        data.pronunciations.splice(pronunciationIndex, 1);
                        ListStore.selectedArray.splice(pronunciationIndex, 1);
                    }

                    setStatus(id, 'done');

                } catch (error) {
                    console.error(`Error uploading pronunciation ID ${id}:`, error);
                    setError(id, error.message || 'Upload failed.');
                }
            }

            StateStore.data.isUploading = false;
            console.log('All recordings uploaded successfully.');
        }
    };

    return {
        data,
        fetchSpeaker,
        saveSpeaker,
        setStatus,
        setError,
        fetchSavedDecks,
        fetchDeck,
        fetchAuto,
        removePronunciation,
        flushQueue,
        stashRecord,
        playRecord,
        discardRecord,
        clearStash,
        uploadRecords,
    };
});
