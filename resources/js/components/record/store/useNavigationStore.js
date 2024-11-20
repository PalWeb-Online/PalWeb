import {computed} from 'vue';
import useStateStore from './useStateStore';
import useRecordStore from './useRecordStore';
import RequestQueue from '../../../utils/RequestQueue.js';

let store;

export default function useNavigationStore() {
    if (!store) {

        const stateStore = useStateStore();
        const recordStore = useRecordStore();
        const requestQueue = RequestQueue;

        const cancel = () => {
            if (confirm('Are you sure you want to leave the wizard?')) {
                window.location.href = '/dashboard/workbench';
            }
        };

        const prev = () => {
            const currentStep = stateStore.data.step;
            const process = stateStore.steps[currentStep]?.canMovePrev();

            if (process === true) {
                stateStore.movePrev();
            } else if (process === false) {
                return;
            } else {
                stateStore.freeze();
                process
                    .then(() => stateStore.movePrev())
                    .finally(() => stateStore.unfreeze());
            }
        };

        const next = () => {
            const currentStep = stateStore.data.step;
            const process = stateStore.steps[currentStep]?.canMoveNext();

            if (process === true) {
                stateStore.moveNext();
            } else if (process === false || process === undefined || process === null) {
                return;
            } else {
                stateStore.freeze();
                process
                    .then(() => stateStore.moveNext())
                    .finally(() => stateStore.unfreeze());
            }
        };

        const retry = () => {
            Object.keys(recordStore.errors).forEach((word) => {
                if (recordStore.errors[word] !== false) {
                    switch (recordStore.status[word]) {
                        case 'ready':
                            recordStore.doStash(word);
                            break;
                        case 'stashed':
                            recordStore.doPublish(word);
                            break;
                        case 'uploaded':
                            recordStore.doFinalize(word);
                            break;
                    }
                }
            });
        };

        store = {
            state: stateStore.data,
            pronunciations: recordStore.data.pronunciations,
            status: recordStore.status,
            errors: recordStore.errors,
            statusCount: recordStore.statusCount,
            cancel,
            prev,
            next,
            retry,
        };
    }

    return store;
}
