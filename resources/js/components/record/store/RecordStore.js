import {defineStore} from 'pinia';
import {reactive, ref} from 'vue';
import axios from 'axios';
import Record from "../../../utils/Record.js";
import RequestQueue from "../../../utils/RequestQueue.js";
import LinguaRecorder from "../../../utils/LinguaRecorder.js";
import {useStateStore} from "./StateStore.js";
import {useSpeakerStore} from "./SpeakerStore.js";
import {useQueueStore} from "./QueueStore.js";

export const useRecordStore = defineStore('RecordStore', () => {
    const data = reactive({
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

    const recorder = ref(null);
    const vumeter = ref(0);
    const saturated = ref(false);
    const StateStore = useStateStore();
    const QueueStore = useQueueStore();
    const SpeakerStore = useSpeakerStore();
    const requestQueue = new RequestQueue();

    const openRecorder = (audioParams) => {
        if (recorder.value) return;

        recorder.value = new LinguaRecorder({
            autoStart: true,
            autoStop: true,
            startThreshold: audioParams.startThreshold,
            stopThreshold: audioParams.stopThreshold,
            saturationThreshold: audioParams.saturationThreshold,
            stopDuration: audioParams.stopDuration,
            marginBefore: audioParams.marginBefore,
            marginAfter: audioParams.marginAfter,
        });

        recorder.value.on('ready', () => {
            console.log('Recorder is ready to go!');
        });

        recorder.value.on('readyFail', showError);

        recorder.value.on('recording', (samples) => {
            console.log('Recorder is recording!');
            vumeter.value = Math.max(...samples) * 1000;
        });

        recorder.value.on('stopped', (record) => {
            onDataAvailable(record);
        });

        recorder.value.on('saturated', () => {
            saturated.value = true;
        });
    };

    const closeRecorder = () => {
        if (!recorder.value) return;

        stopRecording();
        recorder.value.close();
        recorder.value = null;

        console.log('Recorder has been shut off!');
    };

    const startRecording = () => {
        if (!recorder.value) return;

        StateStore.data.isRecording = true;
        recorder.value.start();
        console.log('Recorder is listening!');
    };

    const stopRecording = () => {
        if (!recorder.value) return;

        StateStore.data.isRecording = false;
        vumeter.value = 0;
        saturated.value = false;
        recorder.value.cancel();
        console.log('Recorder has stopped recording!');
    };

    const toggleRecording = () => {
        if (StateStore.data.isRecording) {
            stopRecording();
        } else {
            if (QueueStore.data.items[QueueStore.selected]) startRecording();
        }
    };

    const showError = (error) => {
        const errorMessageAssociation = {
            AbortError: 'Technical issue with the microphone.',
            NotAllowedError: 'Microphone access was not allowed.',
            NotFoundError: 'Microphone not found.',
            NotReadableError: 'Technical issue with the microphone.',
            OverconstrainedError: 'Microphone not found.',
            SecurityError: 'Technical issue with the microphone.',
        };

        alert(errorMessageAssociation[error.name] || 'An unknown error occurred.');
    };

    const onDataAvailable = async (record) => {
        const currentPronunciation = QueueStore.data.items[QueueStore.selected];

        if (currentPronunciation) {
            const blob = record.getBlob();

            if (blob) {
                try {
                    await stashRecord(currentPronunciation, blob);

                    if (data.status[currentPronunciation.id] === 'stashed') {
                        stopRecording();
                        QueueStore.moveForward();

                        if (data.status[QueueStore.data.items[QueueStore.selected]?.id] !== 'stashed') startRecording();
                    }

                } catch (error) {
                    console.error('Error during stashing:', error);
                    stopRecording();
                }
            }
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

    const stashRecord = async (pronunciation, blob) => {
        if (!data.records[pronunciation.id]) {
            data.records[pronunciation.id] = new Record(pronunciation);
            data.records[pronunciation.id].setSpeaker(SpeakerStore.data.speaker);
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
        const id = QueueStore.data.items[QueueStore.selected].id;

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
            //     const nextPronunciationIndex = QueueStore.data.items.findIndex(p => p.id === Number(nextRecordId));
            //     QueueStore.selectItem(nextPronunciationIndex);
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
        fetch(`/api/record/clear/${SpeakerStore.data.speaker.id}`, {method: 'DELETE'})
            .then(response => {
                if (response.ok) {
                    Object.keys(data.status).forEach(key => {
                        if (data.status[key] === 'stashed') {
                            delete data.records[key];
                            delete data.status[key];
                            delete data.errors[key];
                            data.statusCount.stashed--;
                        }
                    });
                    console.log('Stash directory cleaned up successfully.');

                } else {
                    console.error('Failed to clean up stash directory.');
                }

            })
            .catch(error => console.error('Error during stash cleanup:', error));
    };

    const uploadRecords = async () => {
        if (confirm('Are you sure you want to upload all of your recordings?')) {
            StateStore.data.isUploading = true;

            const stashedIds = Object.keys(data.status).filter(
                (id) => data.status[id] === 'stashed'
            );

            for (const id of stashedIds) {
                try {
                    const pronunciationIndex = QueueStore.data.items.findIndex(
                        (p) => p.id === Number(id)
                    );

                    if (pronunciationIndex !== -1) {
                        QueueStore.selectItem(pronunciationIndex);
                    }

                    setStatus(id, 'uploading');

                    const record = data.records[id];
                    if (!record) {
                        throw new Error(`Record with ID ${id} not found.`);
                    }

                    await record.uploadRecord();

                    setStatus(id, 'uploaded');

                    if (pronunciationIndex !== -1) {
                        QueueStore.data.items.splice(pronunciationIndex, 1);
                        QueueStore.selectedArray.splice(pronunciationIndex, 1);
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
        vumeter,
        recorder,
        saturated,
        openRecorder,
        closeRecorder,
        toggleRecording,
        startRecording,
        stopRecording,
        setStatus,
        setError,
        stashRecord,
        playRecord,
        discardRecord,
        clearStash,
        uploadRecords,
    };
});
