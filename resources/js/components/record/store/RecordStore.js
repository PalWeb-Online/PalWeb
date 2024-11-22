import { defineStore } from 'pinia';
import { reactive } from 'vue';
import axios from 'axios';
import Record from "../../../utils/Record.js";
import RequestQueue from "../../../utils/RequestQueue.js";

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
        checkboxes: {},
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
        try {
            const response = await axios.post('/record/pronunciations', {
                dialect_id: data.metadata.speaker.dialect_id,
                listedPronunciations,
            });

            if (response.data) {
                response.data.pronunciations.forEach((pronunciation) => {
                    const exists = data.pronunciations.some(p => p.id === pronunciation.id);
                    if (!exists) {
                        if (!data.records[pronunciation.id]) {
                            data.records[pronunciation.id] = new Record(pronunciation);
                            data.records[pronunciation.id].setSpeaker(data.metadata.speaker);

                            setStatus(pronunciation.id, 'up');
                            setError(pronunciation.id, false);
                        }
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
        delete data.checkboxes[pronunciation];

        data.pronunciations.splice(index, 1);
        return true;
    };

    const doStash = async (pronunciation, blob) => {
        const record = data.records[pronunciation];

        if (!record) {
            console.error(`Record for pronunciation ${pronunciation} not found.`);
            setError(pronunciation, 'Record not found.');
            return Promise.reject('Record not found.');
        }

        setStatus(pronunciation, 'ready');
        setError(pronunciation, false);

        if (blob) {
            try {
                await record.setBlob(blob);
            } catch (error) {
                setError(pronunciation, 'Audio record initialization failed.');
                console.error(error);
                return Promise.reject('Audio record initialization failed.');
            }
        }

        setStatus(pronunciation, 'stashing');

        return new Promise((resolve, reject) => {
            requestQueue.push(async () => {
                try {
                    await record.uploadToStash();
                    data.records[pronunciation].url = record.url;

                    setStatus(pronunciation, 'stashed');
                    console.log(`Successfully stashed: ${pronunciation}`);
                    resolve();
                } catch (error) {
                    setError(pronunciation, error.message || 'Stash upload failed.');
                    console.error(`Error uploading pronunciation ${pronunciation}:`, error);
                    reject(error);
                }
            });
        });
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
    };
});
