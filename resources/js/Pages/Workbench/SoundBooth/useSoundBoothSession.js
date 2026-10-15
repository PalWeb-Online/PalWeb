import {computed, inject, provide, reactive, ref, watch} from 'vue';
import {route} from "ziggy-js";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {useI18n} from "vue-i18n";
import Record from "../../../utils/Record.js";
import RequestQueue from "../../../utils/RequestQueue.js";
import LinguaRecorder from "../../../utils/LinguaRecorder.js";

const soundBoothSessionKey = Symbol("SoundBoothSession");

export function createSoundBoothSession() {
    const {t} = useI18n();
    const NotificationStore = useNotificationStore();

    const data = reactive({
        step: 'speaker',
        testState: 'waiting',
        errorMessage: false,
        hasPermission: false,
        isRecording: false,
        isUploading: false,
        isFrozen: false,
        // isContentVisible: false,
    });

    const speaker = reactive({
        id: null,
        user: {
            id: null,
            name: '',
            avatar_url: '',
        },
        dialect: {
            id: null,
            name: '',
        },
        location: {
            id: '',
            name_ar: '',
            name_en: '',
        },
        fluency: '',
        gender: '',
    });

    const queueItems = reactive([]);
    const selected = ref(0);
    const selectedArray = reactive([]);

    const recordData = reactive({
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
    const timer = ref(null);
    const vumeter = ref(0);
    const saturated = ref(false);

    const audioParams = reactive({
        startThreshold: 0.1,
        stopThreshold: 0.05,
        saturationThreshold: 0.99,
        stopDuration: 0.3,
        marginBefore: 0.25,
        marginAfter: 0.25,
    });

    watch(audioParams, (newParams) => {
        if (recorder) {
            recorder.setConfig(newParams);
        }
    }, {deep: true});

    const requestQueue = new RequestQueue();

    watch(selected, (newIndex) => {
        const container = document.querySelector('section:first-child');
        const selectedItem = container?.querySelectorAll('.rw-record-queue > div')[newIndex];

        if (selectedItem && container) {
            const itemOffsetTop = selectedItem.offsetTop;
            const itemHeight = selectedItem.offsetHeight;
            const containerHeight = container.offsetHeight;

            const scrollPosition = itemOffsetTop - (containerHeight / 2) + (itemHeight / 2);
            container.scrollTo({
                top: scrollPosition,
                behavior: 'smooth',
            });
        }
    });

    const isSelectable = (element) => {
        return !['up', 'ready', 'stashing'].includes(recordData.status[element]);
    };

    const initSelection = () => {
        selectedArray.splice(0, selectedArray.length);
        for (let i = 0; i < queueItems.length; i++) {
            selectedArray.push(false);
        }

        for (let i = 0; i < queueItems.length; i++) {
            if (isSelectable(queueItems[i])) {
                selectItem(i);
                break;
            }
        }
    };

    const selectItem = (index) => {
        selectedArray[selected.value] = false;
        selected.value = index;
        selectedArray[index] = 'selected';

        const newSelected = queueItems[index];
        if (recordData.status[newSelected.id] === 'stashed') {
            if (data.isRecording) stopRecording();
            playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(queueItems[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < queueItems.length; i++) {
            if (isSelectable(queueItems[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const fetchAutoItems = async () => {
        try {
            const {data} = await axios.post(route('sound-booth.get.auto'), {
                speaker_id: speaker.id,
                dialect_id: speaker.dialect.id,
                queuedItems: queueItems,
            });

            queueItems.push(...data.items);
            NotificationStore.addNotification(t('sound-booth.notifications.auto-queue-success', {count: data.items.length}));

        } catch (e) {
            console.error(t('sound-booth.notifications.auto-queue-error'), e);
            NotificationStore.addNotification(t('sound-booth.notifications.auto-queue-error'));
        }
    };

    const fetchDeckItems = async (id) => {
        try {
            const {data} = await axios.post(route('sound-booth.get.deck', id), {
                speaker_id: speaker.id,
                dialect_id: speaker.dialect.id,
                queuedItems: queueItems,
            });

            queueItems.push(...data.items);
            NotificationStore.addNotification(t('sound-booth.notifications.deck-queue-success', {count: data.items.length}));

        } catch (e) {
            console.error(t('sound-booth.notifications.deck-queue-error'), e);
            NotificationStore.addNotification(t('sound-booth.notifications.deck-queue-error'));
        }
    };

    const removeItem = async (pronunciation) => {
        const index = queueItems.indexOf(pronunciation);

        if (recordData.records[pronunciation.id]) {
            if (!confirm(t('sound-booth.notifications.remove-item-confirm'))) return false;

            const discarded = await discardRecord(pronunciation.id);
            if (!discarded) return false;
        }

        queueItems.splice(index, 1);
        return true;
    };

    const flushQueue = async () => {
        if (queueItems.length === 0 || confirm(t('sound-booth.notifications.flush-queue-confirm'))) {
            queueItems.splice(0, queueItems.length);

            await clearStash();
            return true;
        }
        return false;
    };

    const openRecorder = (audioParams) => {
        closeRecorder();

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
        console.log('Recorder has been created!');

        recorder.value.on('ready', () => {
            data.hasPermission = true;
            console.log('Recorder is ready to go!');
        });

        recorder.value.on('readyFail', showError);

        recorder.value.on('recording', (samples) => {
            console.log('Recorder is recording!');
            vumeter.value = Math.max(...samples) * 1000;
        });

        recorder.value.on('stopped', (record) => {
            if (data.step === 'speaker') {
                testPlay(record);
            } else {
                onDataAvailable(record);
            }
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

        data.hasPermission = false;
        console.log('Recorder has been destroyed!');
    };

    const testRecord = () => {
        if (data.testState === 'waiting') {
            data.testState = 'recording';
            recorder.value.start();

            timer.value = setTimeout(() => {
                if (recorder.value) {
                    recorder.value.stop();
                }
            }, 5000);
        }
    };

    const testPlay = (record) => {
        data.testState = 'listening';
        clearTimeout(timer.value);

        const audioNode = record.getAudioElement();
        audioNode.play();

        setTimeout(() => {
            data.testState = 'waiting';
        }, record.getDuration() * 1000 + 300);
    };

    const startRecording = () => {
        if (!recorder.value) return;

        data.isRecording = true;
        recorder.value.start();
        console.log('Recorder is listening!');
    };

    const stopRecording = () => {
        if (!recorder.value) return;

        data.isRecording = false;
        vumeter.value = 0;
        saturated.value = false;
        recorder.value.cancel();
        console.log('Recorder has stopped recording!');
    };

    const toggleRecording = () => {
        if (data.isRecording) {
            stopRecording();
        } else {
            if (queueItems[selected.value]) startRecording();
        }
    };

    const errorMessageAssociation = reactive({
        AbortError: 'Technical issue with the microphone.',
        NotAllowedError: 'Microphone access was not allowed.',
        NotFoundError: 'Microphone not found.',
        NotReadableError: 'Technical issue with the microphone.',
        OverconstrainedError: 'Microphone not found.',
        SecurityError: 'Technical issue with the microphone.',
    });

    const showError = (error) => {
        let message = errorMessageAssociation[error.name] || 'An unknown microphone error occurred.';

        data.errorMessage = message;
        console.error('Microphone Error:', error);
        alert(message);
    };

    const onDataAvailable = async (record) => {
        const currentItem = queueItems[selected.value];

        if (currentItem) {
            const blob = record.getBlob();

            if (blob) {
                try {
                    await stashRecord(currentItem, blob);

                    if (recordData.status[currentItem.id] === 'stashed') {
                        stopRecording();
                        moveForward();

                        if (recordData.status[queueItems[selected.value]?.id] !== 'stashed') startRecording();
                    }

                } catch (error) {
                    console.error('Error during stashing:', error);
                    stopRecording();
                }
            }
        }
    };

    const setStatus = (item, status) => {
        if (!recordData.status[item]) {
            recordData.status[item] = status;
        } else {
            recordData.statusCount[recordData.status[item]]--;
            recordData.status[item] = status;
        }
        recordData.statusCount[status]++;
    };

    const setError = (item, error, message) => {
        if (!recordData.errors[item]) {
            recordData.errors[item] = error;
            data.errorMessage = message;

        } else {
            if (recordData.errors[item] !== false) {
                recordData.statusCount.error--;
            }
            recordData.errors[item] = error;
        }

        if (error !== false) {
            recordData.statusCount.error++;
        }
    };

    const stashRecord = async (item, blob) => {
        if (!recordData.records[item.id]) {
            recordData.records[item.id] = new Record(item);
            recordData.records[item.id].setSpeaker(speaker);
        }

        const record = recordData.records[item.id];
        setError(item.id, false, false);

        setStatus(item.id, 'ready');
        if (blob) {
            try {
                await record.setBlob(blob);

            } catch (error) {
                setError(item.id, error.message, 'Record could not be initialized.');
                throw error;
            }
        }

        setStatus(item.id, 'stashing');
        try {
            await record.stashRecord();
            recordData.records[item.id].url = record.url;

            setStatus(item.id, 'stashed');

        } catch (error) {
            setError(item.id, error.message, 'Record could not be stashed. Please try to record the item again.');
            throw error;
        }
    };

    const playRecord = () => {
        const id = queueItems[selected.value].id;

        if (recordData.status[id] === 'stashed') {
            const record = recordData.records[id];

            if (record?.url) {
                const audio = new Audio(record.url);
                audio.play();
            }
        }
    };

    const discardRecord = async (id) => {
        if (!confirm('Are you sure you want to discard this recording?')) return;

        const record = recordData.records[id];
        if (!record) {
            console.warn(`Record with ID ${id} not found.`);
            return false;
        }

        const stashKey = record.stashKey;
        if (!stashKey) {
            console.error(`No stashKey found for Record with ID ${id}.`);
            return false;
        }

        try {
            await axios.delete(route('stash.destroy', stashKey));

            delete recordData.records[id];
            delete recordData.status[id];
            delete recordData.errors[id];
            recordData.statusCount.stashed--;
            recordData.errors[id] && recordData.statusCount.error--;
            return true;

            // const recordIds = Object.keys(recordData.records);
            //
            // if (recordIds.length > 0) {
            //     const currentIndex = recordIds.indexOf(String(id));
            //     const nextIndex = (currentIndex + 1) < recordIds.length ? currentIndex + 1 : 0;
            //     const nextRecordId = recordIds[nextIndex];
            //     const nextPronunciationIndex = queueItems.findIndex(p => p.id === Number(nextRecordId));
            //     selectItem(nextPronunciationIndex);
            //
            // } else {
            //     console.log("No more records available to select.");
            // }

        } catch (error) {
            console.error(`Error discarding recording with stashKey ${stashKey}:`, error);
            data.errorMessage = 'Record could not be discarded. Please try again.';
            return false;
        }
    };

    const clearStash = async () => {
        try {
            // todo: Ziggy error: 'speakerId' parameter is required for route 'stash.clear'.
            await axios.delete(route('stash.clear', speaker.id));

            Object.keys(recordData.status).forEach(key => {
                if (recordData.status[key] === 'stashed') {
                    delete recordData.records[key];
                    delete recordData.status[key];
                    delete recordData.errors[key];
                    recordData.statusCount.stashed--;
                }
            });

            console.log('Stash directory cleaned up successfully.');

        } catch (error) {
            console.error('Error during stash cleanup:', error.response?.data?.message || error.message);
        }
    };

    const uploadRecords = async () => {
        if (confirm('Are you sure you want to upload all of your recordings?')) {
            data.isUploading = true;

            const stashedIds = Object.keys(recordData.status).filter(
                (id) => recordData.status[id] === 'stashed'
            );

            for (const id of stashedIds) {
                await requestQueue.push(async () => {
                    try {
                        const itemIndex = queueItems.findIndex(
                            (p) => p.id === Number(id)
                        );

                        if (itemIndex !== -1) {
                            selectItem(itemIndex);
                        }

                        setError(id, false, false);
                        setStatus(id, 'uploading');

                        const record = recordData.records[id];
                        if (!record) {
                            throw new Error(`Record with ID ${id} not found.`);
                        }

                        await record.uploadRecord();

                        setStatus(id, 'uploaded');

                        if (itemIndex !== -1) {
                            queueItems.splice(itemIndex, 1);
                            selectedArray.splice(itemIndex, 1);
                        }

                        setStatus(id, 'done');
                    } catch (error) {
                        setError(id, error.message, 'Record could not be uploaded. Please try again.');
                    }
                });
            }

            data.isUploading = false;
            console.log('All recordings uploaded successfully.');
        }
    };

    const steps = {
        speaker: {
            backStep: null,
            nextStep: 'queue',
            canMoveBack: () => false,
            canMoveNext: () => speaker.id && data.hasPermission,
        },
        queue: {
            backStep: null,
            nextStep: 'record',
            canMoveBack: () => false,
            canMoveNext: () => queueItems.length > 0 || recordData.statusCount.done > 0,
        },
        record: {
            backStep: 'queue',
            nextStep: 'check',
            canMoveBack: () => true,
            canMoveNext: () => recordData.statusCount.done > 0,
        },
        check: {
            backStep: 'record',
            nextStep: null,
            canMoveBack: () => true,
            canMoveNext: () => false,
        }
    };

    const backDisabled = computed(() => {
        const canMoveBack = steps[data.step]?.canMoveBack();
        return data.isFrozen || !canMoveBack;
        // return data.isFrozen || hasPendingRequests || !canMoveNext;
    });

    const nextDisabled = computed(() => {
        const canMoveNext = steps[data.step]?.canMoveNext();
        return data.isFrozen || !canMoveNext;
        // return data.isFrozen || hasPendingRequests || !canMoveNext;
    });

    const showRetry = computed(() => {
        return data.step === 'record' && recordData.statusCount.error > 0;
    });

    // TODO: this can kind of be replaced with isRecording (i.e. stashing) & isUploading (i.e. uploading)
    //  since there is no "finalizing"
    const hasPendingRequests = computed(() => {
        const {stashing, uploading, finalizing} = recordData.statusCount;
        return (stashing + uploading + finalizing) > 1;
    });

    const back = async () => {
        const currentStep = steps[data.step];

        if (currentStep?.canMoveBack()) {
            data.step = currentStep.backStep;

        } else {
            freeze();
            currentStep?.canMoveBack()
                .then(() => data.step = currentStep.backStep)
                .finally(() => unfreeze());
        }
    };

    const next = async () => {
        const currentStep = steps[data.step];

        if (currentStep?.canMoveNext()) {
            if (currentStep === 'record' && recordData.statusCount.stashed > 0) {
                if (!confirm('Some of your stashed recordings have not been uploaded yet! You can check your uploaded recordings in the Check step & return to the Record step to continue, but if you exit the Sound Booth without uploading your stashed recordings, they will be deleted.')) return;
            }

            data.step = currentStep.nextStep;

        } else {
            freeze();
            currentStep?.canMoveNext()
                .then(() => data.step = currentStep.nextStep)
                .finally(() => unfreeze());
        }
    };

    function freeze() {
        data.isFrozen = true;
    }

    function unfreeze() {
        data.isFrozen = false;
    }

    const queue = reactive({
        queue: queueItems,
        selected,
        selectedArray,
        initSelection,
        selectItem,
        moveBackward,
        moveForward,
        isSelectable,
        fetchDeckItems,
        fetchAutoItems,
        removeItem,
        flushQueue,
    });

    const record = reactive({
        data: recordData,
        vumeter,
        recorder,
        saturated,
        audioParams,
        openRecorder,
        closeRecorder,
        toggleRecording,
        testRecord,
        testPlay,
        startRecording,
        stopRecording,
        setStatus,
        setError,
        stashRecord,
        playRecord,
        discardRecord,
        clearStash,
        uploadRecords,
    });

    return reactive({
        data,
        speaker,
        steps,
        showRetry,
        hasPendingRequests,
        backDisabled,
        nextDisabled,
        back,
        next,
        queue,
        record,
    });
}

export function provideSoundBoothSession(session) {
    provide(soundBoothSessionKey, session);
    return session;
}

export function useSoundBoothSession() {
    const session = inject(soundBoothSessionKey);

    if (!session) {
        throw new Error("useSoundBoothSession must be used inside a SoundBooth page provider.");
    }

    return session;
}
