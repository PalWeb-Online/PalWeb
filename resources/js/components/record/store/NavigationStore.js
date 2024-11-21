import { defineStore } from 'pinia';
import { useStateStore } from './StateStore';
import { useRecordStore } from './RecordStore';

export const useNavigationStore = defineStore('NavigationStore', () => {
    const StateStore = useStateStore();
    const RecordStore = useRecordStore();

    const cancel = () => {
        if (confirm('Are you sure you want to leave the wizard?')) {
            window.location.href = '/dashboard/workbench';
        }
    };

    const prev = () => {
        const currentStep = StateStore.data.step;
        const process = StateStore.steps[currentStep]?.canMovePrev();

        if (process === true) {
            StateStore.movePrev();
        } else if (process === false) {
            return;
        } else {
            StateStore.freeze();
            process
                .then(() => StateStore.movePrev())
                .finally(() => StateStore.unfreeze());
        }
    };

    const next = () => {
        const currentStep = StateStore.data.step;
        const process = StateStore.steps[currentStep]?.canMoveNext();

        if (process === true) {
            StateStore.moveNext();
        } else if (process === false || process === undefined || process === null) {
            return;
        } else {
            StateStore.freeze();
            process
                .then(() => StateStore.moveNext())
                .finally(() => StateStore.unfreeze());
        }
    };

    const retry = () => {
        Object.keys(RecordStore.errors).forEach((word) => {
            if (RecordStore.errors[word] !== false) {
                switch (RecordStore.status[word]) {
                    case 'ready':
                        RecordStore.doStash(word);
                        break;
                    case 'stashed':
                        RecordStore.doPublish(word);
                        break;
                    case 'uploaded':
                        RecordStore.doFinalize(word);
                        break;
                }
            }
        });
    };

    return {
        state: StateStore.data,
        pronunciations: RecordStore.data.pronunciations,
        status: RecordStore.data.status,
        errors: RecordStore.data.errors,
        statusCount: RecordStore.data.statusCount,
        cancel,
        prev,
        next,
        retry,
    };
});
