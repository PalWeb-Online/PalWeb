import { computed } from 'vue';
import { useStateStore } from './useStateStore';
import { useRecordStore } from './useRecordStore';
import { useStepStore } from './useStepStore';
import { useRequestQueue } from './useRequestQueue'; // Assuming you have a useRequestQueue composable

export function useNavigationStore() {
    const stateStore = useStateStore();
    const recordStore = useRecordStore();
    const stepStore = useStepStore();
    const requestQueue = useRequestQueue();

    const prevDisabled = computed(() => {
        if (stateStore.state.isFrozen) {
            return true;
        }
        return false;
    });

    const nextDisabled = computed(() => {
        if (stateStore.state.isFrozen) {
            return true;
        }

        if (stateStore.state.step === 'details') {
            return recordStore.words.length === 0;
        } else if (stateStore.state.step === 'studio') {
            return recordStore.statusCount.stashed === 0;
        }

        return false; // By default, enable the button
    });

    const showRetry = computed(() => {
        return (stateStore.state.step === 'studio' || stateStore.state.step === 'publish') && recordStore.statusCount.error > 0;
    });

    const fileListUrl = computed(() => {
        // Adjust this logic to your application’s needs
        return `https://commons.wikimedia.org/wiki/Special:ListFiles/USERNAME`;
    });

    const cancel = () => {
        if (confirm('Are you sure you want to leave the wizard?')) {
            window.location.reload(); // This can be replaced with a redirect to a specific route
        }
    };

    const prev = () => {
        const process = stepStore[`${stateStore.state.step}`].canMovePrev();

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
        const process = stepStore[`${stateStore.state.step}`].canMoveNext();

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
        return recordStore.countStatus(['stashing', 'uploading', 'finalizing']) > 0;
    };

    const openFileList = () => {
        window.open(fileListUrl.value, '_blank');
    };

    return {
        state: stateStore.state,
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
