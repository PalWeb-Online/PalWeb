import {defineStore} from 'pinia';
import {computed, reactive} from 'vue';
import {useRecordStore} from "./RecordStore";

export const useStateStore = defineStore('StateStore', () => {
    const RecordStore = useRecordStore();

    const data = reactive({
        step: 'testing',
        isFrozen: false,
        isBrowserReady: false,
        isRecording: false,
        isPublishing: false,
        isContentVisible: false,
    });

    const steps = {
        testing: {
            canMovePrev: () => false,
            canMoveNext: () => true,
        },
        speaker: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.metadata.speaker.name !== '',
        },
        queue: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.pronunciations.length > 0,
        },
        studio: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.statusCount.stashed > 0,
        },
        finish: {
            canMovePrev: () => true,
            canMoveNext: () => false,
        }
    };

    const prevStep = {
        testing: 'testing',
        speaker: 'testing',
        queue: 'speaker',
        studio: 'queue',
        finish: 'studio'
    };

    const nextStep = {
        testing: 'speaker',
        speaker: 'queue',
        queue: 'studio',
        studio: 'finish',
        finish: 'queue'
    };

    const prevDisabled = computed(() => data.isFrozen);

    const nextDisabled = computed(() => {
        const currentStep = data.step;
        const canMoveNext = steps[currentStep]?.canMoveNext();
        return data.isFrozen || !canMoveNext;
    });

    const showRetry = computed(() => {
        return data.step === 'studio' && RecordStore.data.statusCount.error > 0;
    });

    const hasPendingRequests = computed(() => {
        return false;
        // return RecordStore.countStatus(['stashing', 'uploading', 'finalizing']) > 0;
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

    function setBrowserReady(isReady) {
        data.isBrowserReady = isReady;
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
        setBrowserReady,
    };
});
