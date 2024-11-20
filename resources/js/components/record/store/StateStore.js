import {defineStore} from 'pinia';
import {computed, reactive} from 'vue';
import {useRecordStore} from "./RecordStore";

export const useStateStore = defineStore('StateStore', () => {
    const RecordStore = useRecordStore();

    const data = reactive({
        step: 'tutorial',
        isFrozen: false,
        isBrowserReady: false,
        isPublishing: false,
    });

    const steps = {
        tutorial: {
            canMovePrev: () => false,
            canMoveNext: () => true,
        },
        speaker: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.metadata.speaker.name !== '',
        },
        details: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.pronunciations.length > 0,
        },
        studio: {
            canMovePrev: () => true,
            canMoveNext: () => RecordStore.data.statusCount.stashed > 0,
        },
        publish: {
            canMovePrev: () => true,
            canMoveNext: () => false,
        }
    };

    const prevStep = {
        tutorial: 'tutorial',
        speaker: 'tutorial',
        details: 'speaker',
        studio: 'details',
        publish: 'studio'
    };

    const nextStep = {
        tutorial: 'speaker',
        speaker: 'details',
        details: 'studio',
        studio: 'publish',
        publish: 'details'
    };

    const prevDisabled = computed(() => data.isFrozen);

    const nextDisabled = computed(() => {
        const currentStep = data.step;
        const canMoveNext = steps[currentStep]?.canMoveNext();
        return data.isFrozen || !canMoveNext;
    });

    const showRetry = computed(() => {
        return (
            data.step === 'studio' || data.step === 'publish'
        ) && RecordStore.data.statusCount.error > 0;
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
