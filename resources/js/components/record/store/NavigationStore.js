import {defineStore} from 'pinia';
import {useStateStore} from './StateStore';
import {useRecordStore} from './RecordStore';

export const useNavigationStore = defineStore('NavigationStore', () => {
    const StateStore = useStateStore();
    const RecordStore = useRecordStore();

    const prev = async () => {
        const currentStep = StateStore.data.step;

        if (currentStep === 'queue') {
            const flushed = await RecordStore.flushQueue();
            if (!flushed) return;
        }

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

    const next = async () => {
        const currentStep = StateStore.data.step;

        if (currentStep === 'speaker') {
            const saved = await RecordStore.saveSpeaker();
            if (!saved) return;
        }

        if (currentStep === 'studio' && RecordStore.data.statusCount.stashed > 0) {
            if (!confirm('Some of your stashed recordings have not been uploaded yet! You can check your uploaded recordings in the Review step & return to the Studio step to continue, but if you exit the Record Wizard without uploading your stashed recordings, they will be deleted.')) return;
        }

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
        state: StateStore.data,
        pronunciations: RecordStore.data.pronunciations,
        status: RecordStore.data.status,
        errors: RecordStore.data.errors,
        statusCount: RecordStore.data.statusCount,
        prev,
        next,
        retry,
    };
});
