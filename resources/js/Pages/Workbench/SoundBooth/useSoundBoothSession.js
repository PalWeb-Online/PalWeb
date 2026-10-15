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

    const state = reactive({
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

    const queue = reactive([]);
    const selectedItem = ref(0);
    const selectedArray = reactive([]);

    const recordings = reactive({
        items: {},
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

    watch(selectedItem, (newIndex) => {
        const container = document.querySelector('section:first-child');
        const selectedElement = container?.querySelectorAll('.rw-record-queue > div')[newIndex];

        if (selectedElement && container) {
            const itemOffsetTop = selectedElement.offsetTop;
            const itemHeight = selectedElement.offsetHeight;
            const containerHeight = container.offsetHeight;

            const scrollPosition = itemOffsetTop - (containerHeight / 2) + (itemHeight / 2);
            container.scrollTo({
                top: scrollPosition,
                behavior: 'smooth',
            });
        }
    });

    const isSelectable = (element) => {
        return !['up', 'ready', 'stashing'].includes(recordings.status[element]);
    };

    const initSelection = () => {
        selectedArray.splice(0, selectedArray.length);
        for (let i = 0; i < queue.length; i++) {
            selectedArray.push(false);
        }

        for (let i = 0; i < queue.length; i++) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                break;
            }
        }
    };

    const selectItem = (index) => {
        selectedArray[selectedItem.value] = false;
        selectedItem.value = index;
        selectedArray[index] = 'selected';

        const newSelected = queue[index];
        if (recordings.status[newSelected.id] === 'stashed') {
            if (state.isRecording) stopRecording();
            playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selectedItem.value - 1; i >= 0; i--) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selectedItem.value + 1; i < queue.length; i++) {
            if (isSelectable(queue[i])) {
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
                queuedItems: queue,
            });

            queue.push(...data.items);
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
                queuedItems: queue,
            });

            queue.push(...data.items);
            NotificationStore.addNotification(t('sound-booth.notifications.deck-queue-success', {count: data.items.length}));

        } catch (e) {
            console.error(t('sound-booth.notifications.deck-queue-error'), e);
            NotificationStore.addNotification(t('sound-booth.notifications.deck-queue-error'));
        }
    };

    const removeItem = async (pronunciation) => {
        const index = queue.indexOf(pronunciation);

        if (recordings.items[pronunciation.id]) {
            if (!confirm(t('sound-booth.notifications.remove-item-confirm'))) return false;

            const discarded = await discardRecord(pronunciation.id);
            if (!discarded) return false;
        }

        queue.splice(index, 1);
        return true;
    };

    const flushQueue = async () => {
        if (queue.length === 0 || confirm(t('sound-booth.notifications.flush-queue-confirm'))) {
            queue.splice(0, queue.length);

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
            state.hasPermission = true;
            console.log('Recorder is ready to go!');
        });

        recorder.value.on('readyFail', showError);

        recorder.value.on('recording', (samples) => {
            console.log('Recorder is recording!');
            vumeter.value = Math.max(...samples) * 1000;
        });

        recorder.value.on('stopped', (record) => {
            if (state.step === 'speaker') {
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

        state.hasPermission = false;
        console.log('Recorder has been destroyed!');
    };

    const testRecord = () => {
        if (state.testState === 'waiting') {
            state.testState = 'recording';
            recorder.value.start();

            timer.value = setTimeout(() => {
                if (recorder.value) {
                    recorder.value.stop();
                }
            }, 5000);
        }
    };

    const testPlay = (record) => {
        state.testState = 'listening';
        clearTimeout(timer.value);

        const audioNode = record.getAudioElement();
        audioNode.play();

        setTimeout(() => {
            state.testState = 'waiting';
        }, record.getDuration() * 1000 + 300);
    };

    const startRecording = () => {
        if (!recorder.value) return;

        state.isRecording = true;
        recorder.value.start();
        console.log('Recorder is listening!');
    };

    const stopRecording = () => {
        if (!recorder.value) return;

        state.isRecording = false;
        vumeter.value = 0;
        saturated.value = false;
        recorder.value.cancel();
        console.log('Recorder has stopped recording!');
    };

    const toggleRecording = () => {
        if (state.isRecording) {
            stopRecording();
        } else {
            if (queue[selectedItem.value]) startRecording();
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

        state.errorMessage = message;
        console.error('Microphone Error:', error);
        alert(message);
    };

    const onDataAvailable = async (record) => {
        const currentItem = queue[selectedItem.value];

        if (currentItem) {
            const blob = record.getBlob();

            if (blob) {
                try {
                    await stashRecord(currentItem, blob);

                    if (recordings.status[currentItem.id] === 'stashed') {
                        stopRecording();
                        moveForward();

                        if (recordings.status[queue[selectedItem.value]?.id] !== 'stashed') startRecording();
                    }

                } catch (error) {
                    console.error('Error during stashing:', error);
                    stopRecording();
                }
            }
        }
    };

    const setStatus = (item, status) => {
        if (!recordings.status[item]) {
            recordings.status[item] = status;
        } else {
            recordings.statusCount[recordings.status[item]]--;
            recordings.status[item] = status;
        }
        recordings.statusCount[status]++;
    };

    const setError = (item, error, message) => {
        if (!recordings.errors[item]) {
            recordings.errors[item] = error;
            state.errorMessage = message;

        } else {
            if (recordings.errors[item] !== false) {
                recordings.statusCount.error--;
            }
            recordings.errors[item] = error;
        }

        if (error !== false) {
            recordings.statusCount.error++;
        }
    };

    const stashRecord = async (item, blob) => {
        if (!recordings.items[item.id]) {
            recordings.items[item.id] = new Record(item);
            recordings.items[item.id].setSpeaker(speaker);
        }

        const record = recordings.items[item.id];
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
            recordings.items[item.id].url = record.url;

            setStatus(item.id, 'stashed');

        } catch (error) {
            setError(item.id, error.message, 'Record could not be stashed. Please try to record the item again.');
            throw error;
        }
    };

    const playRecord = () => {
        const id = queue[selectedItem.value].id;

        if (recordings.status[id] === 'stashed') {
            const record = recordings.items[id];

            if (record?.url) {
                const audio = new Audio(record.url);
                audio.play();
            }
        }
    };

    const discardRecord = async (id) => {
        if (!confirm('Are you sure you want to discard this recording?')) return;

        const record = recordings.items[id];
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

            delete recordings.items[id];
            delete recordings.status[id];
            delete recordings.errors[id];
            recordings.statusCount.stashed--;
            recordings.errors[id] && recordings.statusCount.error--;
            return true;

            // const recordIds = Object.keys(recordings.items);
            //
            // if (recordIds.length > 0) {
            //     const currentIndex = recordIds.indexOf(String(id));
            //     const nextIndex = (currentIndex + 1) < recordIds.length ? currentIndex + 1 : 0;
            //     const nextRecordId = recordIds[nextIndex];
            //     const nextPronunciationIndex = queue.findIndex(p => p.id === Number(nextRecordId));
            //     selectItem(nextPronunciationIndex);
            //
            // } else {
            //     console.log("No more items available to select.");
            // }

        } catch (error) {
            console.error(`Error discarding recording with stashKey ${stashKey}:`, error);
            state.errorMessage = 'Record could not be discarded. Please try again.';
            return false;
        }
    };

    const clearStash = async () => {
        try {
            // todo: Ziggy error: 'speakerId' parameter is required for route 'stash.clear'.
            await axios.delete(route('stash.clear', speaker.id));

            Object.keys(recordings.status).forEach(key => {
                if (recordings.status[key] === 'stashed') {
                    delete recordings.items[key];
                    delete recordings.status[key];
                    delete recordings.errors[key];
                    recordings.statusCount.stashed--;
                }
            });

            console.log('Stash directory cleaned up successfully.');

        } catch (error) {
            console.error('Error during stash cleanup:', error.response?.data?.message || error.message);
        }
    };

    const uploadRecords = async () => {
        if (confirm('Are you sure you want to upload all of your recordings?')) {
            state.isUploading = true;

            const stashedIds = Object.keys(recordings.status).filter(
                (id) => recordings.status[id] === 'stashed'
            );

            for (const id of stashedIds) {
                await requestQueue.push(async () => {
                    try {
                        const itemIndex = queue.findIndex(
                            (p) => p.id === Number(id)
                        );

                        if (itemIndex !== -1) {
                            selectItem(itemIndex);
                        }

                        setError(id, false, false);
                        setStatus(id, 'uploading');

                        const record = recordings.items[id];
                        if (!record) {
                            throw new Error(`Record with ID ${id} not found.`);
                        }

                        await record.uploadRecord();

                        setStatus(id, 'uploaded');

                        if (itemIndex !== -1) {
                            queue.splice(itemIndex, 1);
                            selectedArray.splice(itemIndex, 1);
                        }

                        setStatus(id, 'done');
                    } catch (error) {
                        setError(id, error.message, 'Record could not be uploaded. Please try again.');
                    }
                });
            }

            state.isUploading = false;
            console.log('All recordings uploaded successfully.');
        }
    };

    const steps = {
        speaker: {
            backStep: null,
            nextStep: 'queue',
            canMoveBack: () => false,
            canMoveNext: () => speaker.id && state.hasPermission,
        },
        queue: {
            backStep: null,
            nextStep: 'record',
            canMoveBack: () => false,
            canMoveNext: () => queue.length > 0 || recordings.statusCount.done > 0,
        },
        record: {
            backStep: 'queue',
            nextStep: 'check',
            canMoveBack: () => true,
            canMoveNext: () => recordings.statusCount.done > 0,
        },
        check: {
            backStep: 'record',
            nextStep: null,
            canMoveBack: () => true,
            canMoveNext: () => false,
        }
    };

    const prevDisabled = computed(() => {
        const canMoveBack = steps[state.step]?.canMoveBack();
        return state.isFrozen || !canMoveBack;
        // return state.isFrozen || hasPendingRequests || !canMoveNextPage;
    });

    const nextDisabled = computed(() => {
        const canMoveNext = steps[state.step]?.canMoveNext();
        return state.isFrozen || !canMoveNext;
        // return state.isFrozen || hasPendingRequests || !canMoveNextPage;
    });

    const showRetry = computed(() => {
        return state.step === 'record' && recordings.statusCount.error > 0;
    });

    // TODO: this can kind of be replaced with isRecording (i.e. stashing) & isUploading (i.e. uploading)
    //  since there is no "finalizing"
    const hasPendingRequests = computed(() => {
        const {stashing, uploading, finalizing} = recordings.statusCount;
        return (stashing + uploading + finalizing) > 1;
    });

    const prevPage = async () => {
        const currentStep = steps[state.step];

        if (currentStep?.canMoveBack()) {
            state.step = currentStep.backStep;

        } else {
            freeze();
            currentStep?.canMoveBack()
                .then(() => state.step = currentStep.backStep)
                .finally(() => unfreeze());
        }
    };

    const nextPage = async () => {
        const currentStep = steps[state.step];

        if (currentStep?.canMoveNext()) {
            if (currentStep === 'record' && recordings.statusCount.stashed > 0) {
                if (!confirm('Some of your stashed recordings have not been uploaded yet! You can check your uploaded recordings in the Check step & return to the Record step to continue, but if you exit the Sound Booth without uploading your stashed recordings, they will be deleted.')) return;
            }

            state.step = currentStep.nextStep;

        } else {
            freeze();
            currentStep?.canMoveNext()
                .then(() => state.step = currentStep.nextStep)
                .finally(() => unfreeze());
        }
    };

    function freeze() {
        state.isFrozen = true;
    }

    function unfreeze() {
        state.isFrozen = false;
    }

    return reactive({
        state,
        speaker,
        steps,
        showRetry,
        hasPendingRequests,
        prevDisabled,
        nextDisabled,
        prevPage,
        nextPage,
        queue,
        selectedItem,
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
        recordings,
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
