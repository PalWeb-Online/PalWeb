import { reactive } from 'vue';
import axios from 'axios';

let store;

export default function useRecordStore() {
    if (!store) {

        const data = reactive({
            metadata: {
                language: '',
                license: '',
                media: '',
                speaker: {
                    name: '',
                    user_id: null,
                    dialect_id: null,
                    location_id: null,
                    gender: null,
                },
            },
            words: [],
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

        const setStatus = (word, status) => {
            if (data.status[word] === undefined) {
                data.status[word] = status;
            } else {
                data.statusCount[data.status[word]]--;
                data.status[word] = status;
            }
            data.statusCount[status]++;
        };

        const setError = (word, error) => {
            if (data.errors[word] === undefined) {
                data.errors[word] = error;
            } else {
                if (data.errors[word] !== false) {
                    data.statusCount.error--;
                }
                data.errors[word] = error;
            }

            if (error !== false) {
                data.statusCount.error++;
            }
        };

        const clearRecord = (word) => {
            const index = data.words.indexOf(word);

            if (index === -1) return false;

            data.statusCount[data.status[word]]--;
            if (data.errors[word] !== false) {
                data.statusCount.error--;
            }

            delete data.records[word];
            delete data.status[word];
            delete data.errors[word];
            delete data.checkboxes[word];

            data.words.splice(index, 1);
            return true;
        };

        // is this used anywhere other than in setSpeaker? I removed that because it's not actually possible to change Speakers
        const clearAllRecords = () => {
            data.words.forEach((word) => {
                delete data.records[word];
                delete data.status[word];
                delete data.errors[word];
                delete data.checkboxes[word];
            });

            Object.keys(data.statusCount).forEach((key) => {
                data.statusCount[key] = 0;
            });

            data.words.splice(0, data.words.length);
        };

        const addWords = (words) => {
            words = Array.isArray(words) ? words : [words];

            words.forEach((word) => {
                const trimmedWord = word.replace(/\t/g, '').trim();
                if (trimmedWord === '') return;

                if (!data.words.includes(trimmedWord)) {
                    if (!data.records[trimmedWord]) {
                        data.records[trimmedWord] = {}; // Replace with appropriate Record logic if needed
                        data.records[trimmedWord].setLanguage(data.metadata.language);
                        data.records[trimmedWord].setSpeaker(data.metadata.speaker);
                        data.records[trimmedWord].setLicense(data.metadata.license);

                        setStatus(trimmedWord, 'up');
                        setError(trimmedWord, false);
                    }
                    data.words.push(trimmedWord);
                }
            });
        };

        const randomizeList = () => {
            for (let i = data.words.length - 1; i >= 0; i--) {
                const randomIndex = Math.floor(Math.random() * (i + 1));
                const temp = data.words[randomIndex];
                data.words[randomIndex] = data.words[i];
                data.words[i] = temp;
            }
        };

        const doStash = (word, blob) => {
            setStatus(word, 'ready');
            setError(word, false);

            if (blob !== undefined) {
                data.records[word].setBlob(blob, data.metadata.media === 'audio' ? 'wav' : 'webm');
            }

            setStatus(word, 'stashing');
            // Assuming requestQueue and API logic will be integrated here
        };

        store = {
            data,
            fetchSpeaker,
            saveSpeaker,
            setStatus,
            setError,
            clearRecord,
            clearAllRecords,
            addWords,
            randomizeList,
            doStash,
        };
    }

    return store;
}
