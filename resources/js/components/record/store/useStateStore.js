import { reactive } from 'vue';

export function useStateStore() {
    const data = reactive({
        step: 'tutorial',
        isFrozen: false,
        isBrowserReady: false,
        isPublishing: false
    });

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

    return {
        data,
        movePrev,
        moveNext,
        freeze,
        unfreeze
    };
}
