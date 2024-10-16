import { reactive } from 'vue';

let store;

export default function useStateStore() {
    if (!store) {
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

        function setBrowserReady(isReady) {
            data.isBrowserReady = isReady;
        }

        store = {
            data,
            movePrev,
            moveNext,
            freeze,
            unfreeze,
            setBrowserReady,
        };
    }

    return store;
}
