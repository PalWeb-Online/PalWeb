import { reactive } from 'vue';
import useRecordStore from './useRecordStore';

let store;

export default function useStepStore() {
    if (!store) {
        const recordStore = useRecordStore();

        const steps = {
            tutorial: {
                canMovePrev: () => false,
                canMoveNext: () => true,
            },
            speaker: {
                canMovePrev: () => true,
                canMoveNext: () => recordStore.data.metadata.speaker.name !== '',
            },
            details: {
                canMovePrev: () => true,
                canMoveNext: () => recordStore.data.pronunciations.length > 0,
            },
            studio: {
                canMovePrev: () => true,
                canMoveNext: () => recordStore.data.statusCount.stashed > 0,
            },
            publish: {
                canMovePrev: () => true,
                canMoveNext: () => false,
            }
        };

        store = {
            steps,
        };
    }

    return store;
}
