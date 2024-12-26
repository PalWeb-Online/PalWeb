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
        errorMessage: false,
        hasPermission: false,
        isRecording: false,
        isUploading: false,
        isFrozen: false,
        // isContentVisible: false,
    });

    const steps = {
        speaker: {
            backStep: null,
            nextStep: 'queue',
            canMoveBack: () => false,
            canMoveNext: () => data.hasPermission && SpeakerStore.data.speaker.exists,
        },
        queue: {
            backStep: null,
            nextStep: 'record',
            canMoveBack: () => false,
            canMoveNext: () => QueueStore.data.items.length > 0 || RecordStore.data.statusCount.done > 0,
        },
        record: {
            backStep: 'queue',
            nextStep: 'check',
            canMoveBack: () => true,
            canMoveNext: () => RecordStore.data.statusCount.done > 0,
        },
        check: {
            backStep: 'record',
            nextStep: null,
            canMoveBack: () => true,
            canMoveNext: () => false,
        }
    };

    const backDisabled = computed(() => {
        const canMoveBack = steps[data.step]?.canMoveBack();
        return data.isFrozen || !canMoveBack;
        // return data.isFrozen || hasPendingRequests || !canMoveNext;
    });

    const nextDisabled = computed(() => {
        const canMoveNext = steps[data.step]?.canMoveNext();
        return data.isFrozen || !canMoveNext;
        // return data.isFrozen || hasPendingRequests || !canMoveNext;
    });

    const showRetry = computed(() => {
        return data.step === 'record' && RecordStore.data.statusCount.error > 0;
    });

    // TODO: this can kind of be replaced with isRecording (i.e. stashing) & isUploading (i.e. uploading)
    //  since there is no "finalizing"
    const hasPendingRequests = computed(() => {
        const {stashing, uploading, finalizing} = RecordStore.data.statusCount;
        return (stashing + uploading + finalizing) > 1;
    });

    const back = async () => {
        const currentStep = steps[data.step];

        if (currentStep?.canMoveBack()) {
            data.step = currentStep.backStep;

        } else {
            freeze();
            currentStep?.canMoveBack()
                .then(() => data.step = currentStep.backStep)
                .finally(() => unfreeze());
        }
    };

    const next = async () => {
        const currentStep = steps[data.step];

        if (currentStep?.canMoveNext()) {
            if (currentStep === 'speaker') {
                const saved = await SpeakerStore.saveSpeaker();
                if (!saved) return;
            }
            if (currentStep === 'record' && RecordStore.data.statusCount.stashed > 0) {
                if (!confirm('Some of your stashed recordings have not been uploaded yet! You can check your uploaded recordings in the Check step & return to the Record step to continue, but if you exit the Record Wizard without uploading your stashed recordings, they will be deleted.')) return;
            }

            data.step = currentStep.nextStep;

        } else {
            freeze();
            currentStep?.canMoveNext()
                .then(() => data.step = currentStep.nextStep)
                .finally(() => unfreeze());
        }
    };

    function setPermission(isReady) {
        data.hasPermission = isReady;
    }

    function freeze() {
        data.isFrozen = true;
    }

    function unfreeze() {
        data.isFrozen = false;
    }

    return {
        data,
        steps,
        showRetry,
        hasPendingRequests,
        backDisabled,
        nextDisabled,
        back,
        next,
        setPermission,
    };
});
