import {defineStore} from 'pinia';
import {reactive} from 'vue';
import axios from 'axios';
import Record from "../../../utils/Record.js";
import RequestQueue from "../../../utils/RequestQueue.js";
import {useListStore} from "./ListStore.js";
import {useStateStore} from "./StateStore.js";

export const useRecordStore = defineStore('RecordStore', () => {
    const data = reactive({
        metadata: {
            speaker: {
                name: '',
                user_id: null,
                dialect_id: null,
                location_id: null,
                gender: null,
            },
        },
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
        try {
            const response = await axios.get('/record/speaker');
            if (response.data) {
                data.metadata.speaker = response.data.speaker;
                data.metadata.speaker.name = response.data.name;
            }
        } catch (error) {
            console.error('Error loading speaker:', error);
        }
    };

    const saveSpeaker = async () => {
        try {
            const response = await axios.post('/record/speaker', {
                user_id: data.metadata.speaker.user_id,
                dialect_id: data.metadata.speaker.dialect_id,
                location_id: data.metadata.speaker.location_id,
                gender: data.metadata.speaker.gender,
            });

            alert(response.data.message);
            data.metadata.speaker = response.data.speaker;
            data.metadata.speaker.name = response.data.name;

        } catch (error) {
            console.error('Error saving speaker:', error);
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

    const fetchPronunciations = async (listedPronunciations = []) => {
        // TODO: discard pronunciations that had previously been loaded in
        // e.g. check if there is already data.records[pronunciation.id]

        try {
            const response = await axios.post('/record/pronunciations', {
                dialect_id: data.metadata.speaker.dialect_id,
                listedPronunciations,
            });

            if (response.data) {
                response.data.pronunciations.forEach((pronunciation) => {
                    const exists = data.pronunciations.some(p => p.id === pronunciation.id);
                    if (!exists) {
                        data.pronunciations.push(pronunciation);
                    }
                });
            }

        } catch (error) {
            console.error('Error loading pronunciations:', error);
        }
    };

    const removePronunciation = (pronunciation) => {
        const index = data.pronunciations.indexOf(pronunciation);

        if (index === -1) return false;

        data.statusCount[data.status[pronunciation]]--;
        if (data.errors[pronunciation] !== false) {
            data.statusCount.error--;
        }

        delete data.records[pronunciation];
        delete data.status[pronunciation];
        delete data.errors[pronunciation];

        data.pronunciations.splice(index, 1);
        return true;
    };

    const doStash = async (pronunciation, blob) => {
        if (!data.records[pronunciation.id]) {
            data.records[pronunciation.id] = new Record(pronunciation);
            data.records[pronunciation.id].setSpeaker(data.metadata.speaker);
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

                return Promise.reject('Audio record initialization failed.');
            }
        }

        setStatus(pronunciation.id, 'stashing');

        return new Promise((resolve, reject) => {
            requestQueue.push(async () => {
                try {
                    await record.uploadToStash();
                    data.records[pronunciation.id].url = record.url;

                    setStatus(pronunciation.id, 'stashed');
                    resolve();

                } catch (error) {
                    setError(pronunciation.id, error.message || 'Stash upload failed.');
                    console.error(`Error uploading pronunciation ${pronunciation.translit}:`, error);
                    reject(error);
                }
            });
        });
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
        if (confirm('Are you sure you want to discard this recording?')) {
            const record = data.records[id];
            if (!record) {
                console.warn(`Record with ID ${id} not found.`);
                return;
            }

            const stashkey = record.stashkey;
            if (!stashkey) {
                console.error(`No stashkey found for Record with ID ${id}.`);
                return;
            }

            try {
                const response = await axios.delete(`/api/record/discard-record/${stashkey}`);

                if (response.status === 200) {
                    console.log(`Recording with stashkey ${stashkey} discarded successfully.`);

                } else {
                    console.error(`Failed to discard recording with stashkey ${stashkey}.`);
                }

                delete data.records[id];
                delete data.status[id];
                delete data.errors[id];
                data.statusCount.stashed--;

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
            }
        }
    };

    const uploadRecords = async () => {
        // TODO: Stop recording before uploading.

        if (confirm('Are you sure you want to upload all of your recordings?')) {
            StateStore.data.isPublishing = true;

            const stashedIds = Object.keys(data.status).filter(
                (id) => data.status[id] === 'stashed'
            );

            for (const id of stashedIds) {
                try {
                    const pronunciationIndex = data.pronunciations.findIndex(
                        (p) => p.id === Number(id)
                    );
                    ListStore.selectWord(pronunciationIndex);

                    setStatus(id, 'uploading');
                    await new Promise((resolve) => setTimeout(resolve, 500));

                    setStatus(id, 'uploaded');

                    if (pronunciationIndex !== -1) {
                        data.pronunciations.splice(pronunciationIndex, 1);
                        ListStore.selectedArray.splice(pronunciationIndex, 1);
                    }

                    setStatus(id, 'done');

                } catch (error) {
                    console.error(`Simulated upload failed for record ID: ${id}`, error);
                    setError(id, 'Upload simulation failed.');
                }
            }

            StateStore.data.isPublishing = false;
            console.log('All recordings uploaded successfully.');
        }
    };

    return {
        data,
        fetchSpeaker,
        saveSpeaker,
        setStatus,
        setError,
        fetchPronunciations,
        removePronunciation,
        doStash,
        playRecord,
        discardRecord,
        uploadRecords,
    };
});
