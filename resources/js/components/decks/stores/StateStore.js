import {defineStore} from 'pinia';
import {computed, reactive} from "vue";

export const useStateStore = defineStore('StateStore', () => {
    const data = reactive({
        context: null,
        action: 'create',
        step: 'select',
        errorMessage: '',
    });

    const initialize = (action = 'create') => {
        data.action = action;
        if (action === 'edit') {
            data.step = 'build';
        }
    };

    const steps = computed(() => {
        const isBuilder = data.context === 'builder';
        return {
            select: {
                backStep: null,
                nextStep: isBuilder ? 'build' : 'study',
                canMoveBack: () => false,
                canMoveNext: () => true,
            },
            build: {
                backStep: 'select',
                nextStep: null,
                canMoveBack: () => true,
                canMoveNext: () => false,
            },
            study: {
                backStep: 'select',
                nextStep: null,
                canMoveBack: () => true,
                canMoveNext: () => false,
            }
        }
    });

    const backDisabled = computed(() => !steps.value[data.step]?.canMoveBack());
    const nextDisabled = computed(() => !steps.value[data.step]?.canMoveNext());

    const back = async () => {
        const currentStep = steps.value[data.step];
        if (currentStep?.canMoveBack()) {
            if (data.step === 'select') {
                data.step = currentStep.backStep;

            } else if (data.step === 'build') {
                if (!confirm('Are you sure you would like to return to the Select page? All your unsaved changes will be lost.')) return;

                data.step = currentStep.backStep;

            } else {
                data.step = currentStep.backStep;
            }
        }
    };

    const next = async () => {
        const currentStep = steps.value[data.step];

        if (currentStep?.canMoveNext()) {
            if (data.step === 'select') {
                data.step = currentStep.nextStep;

            } else if (data.step === 'study') {
                data.step = currentStep.nextStep;

            } else {
                data.step = currentStep.nextStep;
            }
        }
    };

    const exit = async () => {
        window.location.href = '/dashboard/workbench';
    };

    return {
        data,
        initialize,
        backDisabled,
        nextDisabled,
        back,
        next,
        exit
    };
});
