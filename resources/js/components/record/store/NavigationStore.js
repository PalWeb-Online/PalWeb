import {defineStore} from 'pinia';
import {useStateStore} from './StateStore';
import {useRecordStore} from './RecordStore';
import {useQueueStore} from "./QueueStore.js";
import {useSpeakerStore} from "./SpeakerStore.js";

export const useNavigationStore = defineStore('NavigationStore', () => {
    const StateStore = useStateStore();
    const QueueStore = useQueueStore();
    const SpeakerStore = useSpeakerStore();
    const RecordStore = useRecordStore();

    const back = async () => {
        const currentStep = StateStore.data.step;

        const process = StateStore.steps[currentStep]?.canMoveBack();

        if (process === true) {
            StateStore.moveBack();

        } else if (process === false) {
            return;

        } else {
            StateStore.freeze();
            process
                .then(() => StateStore.moveBack())
                .finally(() => StateStore.unfreeze());
        }
    };

    const next = async () => {
        const currentStep = StateStore.data.step;
        const process = StateStore.steps[currentStep]?.canMoveNext();

        if (process === true) {
            if (currentStep === 'speaker') {
                const saved = await SpeakerStore.saveSpeaker();
                if (!saved) return;
            }

            if (currentStep === 'record' && RecordStore.data.statusCount.stashed > 0) {
                if (!confirm('Some of your stashed recordings have not been uploaded yet! You can check your uploaded recordings in the Check step & return to the Record step to continue, but if you exit the Record Wizard without uploading your stashed recordings, they will be deleted.')) return;
            }

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
        Object.keys(RecordStore.data.errors).forEach((word) => {
            if (RecordStore.data.errors[word] !== false) {
                switch (RecordStore.data.status[word]) {
                    case 'ready':
                        RecordStore.stashRecord(word);
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
        back,
        next,
        retry,
    };
});
