import {computed} from 'vue';
import useStateStore from './useStateStore';
import useRecordStore from './useRecordStore';
import useStepStore from './useStepStore';
import RequestQueue from '../../../utils/RequestQueue.js';

let store;

export default function useNavigationStore() {
    if (!store) {

        const stateStore = useStateStore();
        const recordStore = useRecordStore();
        const stepStore = useStepStore();
        const requestQueue = RequestQueue;

        const prevDisabled = computed(() => stateStore.data.isFrozen);

        const nextDisabled = computed(() => {
            const currentStep = stateStore.data.step;
            const canMoveNext = stepStore.steps[currentStep]?.canMoveNext();
            return stateStore.data.isFrozen || !canMoveNext;
        });

        const showRetry = computed(() => {
            return (
                stateStore.data.step === 'studio' || stateStore.data.step === 'publish'
            ) && recordStore.data.statusCount.error > 0;
        });

        const fileListUrl = computed(() => {
            // Adjust this logic to your application’s needs
            return `https://commons.wikimedia.org/wiki/Special:ListFiles/USERNAME`;
        });

        const cancel = () => {
            if (confirm('Are you sure you want to leave the wizard?')) {
                window.location.href = '/dashboard/workbench';
            }
        };

        const prev = () => {
            const currentStep = stateStore.data.step;
            const process = stepStore.steps[currentStep]?.canMovePrev();

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
            const process = stepStore.steps[currentStep]?.canMoveNext();

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

        const hasPendingRequests = () => {
            return false;
            // return recordStore.countStatus(['stashing', 'uploading', 'finalizing']) > 0;
        };

        const openFileList = () => {
            window.open(fileListUrl.value, '_blank');
        };

        store = {
            state: stateStore.data,
            words: recordStore.words,
            status: recordStore.status,
            errors: recordStore.errors,
            statusCount: recordStore.statusCount,
            prevDisabled,
            nextDisabled,
            showRetry,
            fileListUrl,
            cancel,
            prev,
            next,
            retry,
            hasPendingRequests,
            openFileList,
        };
    }

    return store;
}
