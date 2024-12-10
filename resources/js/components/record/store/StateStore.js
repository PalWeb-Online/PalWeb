import {defineStore} from 'pinia';
import {computed, reactive} from 'vue';
import {useRecordStore} from "./RecordStore";
import {useSpeakerStore} from "./SpeakerStore.js";
import {useQueueStore} from "./QueueStore.js";

export const useStateStore = defineStore('StateStore', () => {
    const SpeakerStore = useSpeakerStore();
    const QueueStore = useQueueStore();
    const RecordStore = useRecordStore();

    const data = reactive({
        step: 'speaker',
        testState: 'ready',
        hasPermission: false,
        isRecording: false,
        isUploading: false,

        isFrozen: false,
        // isContentVisible: false,
    });

    const steps = {
        speaker: {
            canMovePrev: () => false,
            canMoveNext: () => data.hasPermission && SpeakerStore.data.speaker.exists,
        },
        queue: {
            canMovePrev: () => true,
            canMoveNext: () => QueueStore.data.items.length > 0,
        },
        studio: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.statusCount.done > 0,
        },
        review: {
            canMovePrev: () => true,
            canMoveNext: () => false,
        }
    };

    const prevStep = {
        speaker: 'speaker',
        queue: 'speaker',
        studio: 'queue',
        review: 'studio'
    };

    const nextStep = {
        speaker: 'queue',
        queue: 'studio',
        studio: 'review',
        review: 'queue'
    };

    const prevDisabled = computed(() => {
        const currentStep = data.step;
        const canMovePrev = steps[currentStep]?.canMovePrev();
        return data.isFrozen || !canMovePrev;
        // return data.isFrozen || hasPendingRequests || !canMoveNext;
    });

    const nextDisabled = computed(() => {
        const currentStep = data.step;
        const canMoveNext = steps[currentStep]?.canMoveNext();
        return data.isFrozen || !canMoveNext;
        // return data.isFrozen || hasPendingRequests || !canMoveNext;
    });

    const showRetry = computed(() => {
        return data.step === 'studio' && RecordStore.data.statusCount.error > 0;
    });

    // TODO: this can kind of be replaced with isRecording (i.e. stashing) & isUploading (i.e. uploading)
    //  since there is no "finalizing"
    const hasPendingRequests = computed(() => {
        const {stashing, uploading, finalizing} = RecordStore.data.statusCount;
        return (stashing + uploading + finalizing) > 1;
    });

    function movePrev() {
        data.step = prevStep[data.step];
    }

    function moveNext() {
        data.step = nextStep[data.step];
    }

    function freeze() {
        data.isFrozen = true;
    }

    function unfreeze() {
        data.isFrozen = false;
    }


    /**
     * Sets the readiness status of the browser (i.e. if the user agent has allowed usage of the microphone).
     *
     * @param {boolean} isReady - The readiness status of the browser. True if the browser is ready, false otherwise.
     *
     * @return {void}
     */
    function setPermission(isReady) {
        data.hasPermission = isReady;
    }

    return {
        data,
        steps,
        prevDisabled,
        nextDisabled,
        showRetry,
        hasPendingRequests,
        movePrev,
        moveNext,
        freeze,
        unfreeze,
        setPermission,
    };
});
