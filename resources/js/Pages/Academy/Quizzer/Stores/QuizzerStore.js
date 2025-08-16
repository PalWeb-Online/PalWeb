import {defineStore} from 'pinia';
import {reactive, ref} from 'vue';
import {shuffle} from "lodash";

export const useQuizzerStore = defineStore('QuizzerStore', () => {
    const data = reactive({
        step: 'setup',
        quizType: '',
        model: null,
    });

    const quiz = ref([]);
    const score = ref(0);

    // const steps = {
    //     setup: {
    //         backStep: null,
    //         nextStep: 'quiz',
    //         canMoveBack: () => false,
    //         canMoveNext: () => true,
    //     },
    //     quiz: {
    //         backStep: 'setup',
    //         nextStep: 'results',
    //         canMoveBack: () => true,
    //         canMoveNext: () => QueueStore.queue.length > 0 || RecordStore.data.statusCount.done > 0,
    //     },
    //     results: {
    //         backStep: 'quiz',
    //         nextStep: null,
    //         canMoveBack: () => false,
    //         canMoveNext: () => false,
    //     },
    // };
    //
    // const back = async () => {
    //     const currentStep = steps[data.step];
    //
    //     if (currentStep?.canMoveBack()) {
    //         data.step = currentStep.backStep;
    //
    //     } else {
    //         currentStep?.canMoveBack()
    //             .then(() => data.step = currentStep.backStep)
    //             .finally(() => unfreeze());
    //     }
    // };
    //
    // const next = async () => {
    //     const currentStep = steps[data.step];
    //
    //     if (currentStep?.canMoveNext()) {
    //         if (currentStep === 'setup') {
    //             if (!confirm('test 1')) return;
    //         }
    //
    //         if (currentStep === 'quiz') {
    //             if (!confirm('test 2')) return;
    //         }
    //
    //         data.step = currentStep.nextStep;
    //
    //     } else {
    //         currentStep?.canMoveNext().then(() => data.step = currentStep.nextStep)
    //     }
    // };

    const startQuiz = (quizSettings) => {
        generateQuiz(quizSettings).then(() => {
            data.step = 'quiz';
        });
    };

    const submitQuiz = () => {
        scoreQuiz();
        data.step = 'results';
    }

    const generateQuiz = async (quizSettings) => {
        try {
            const response = await axios.get(route('quizzer.deck.quiz', data.model), {
                params: {settings: quizSettings}
            });

            quiz.value = response.data.quiz;

            quiz.value.forEach(question => {
                shuffle(Object.entries(question.options));
            })

        } catch (error) {
            console.error('Failed to generate quiz', error);
        }
    };

    const scoreQuiz = () => {
        quiz.value.forEach(item => {
            if (item.selection === item.answer) {
                score.value += 1;
            }
        });
    };

    const startOver = () => {
        data.step = 'setup';
        quiz.value = [];
        score.value = 0;
    };

    const reset = () => {
        data.step = 'setup';
        data.quizType = '';
        data.model = null;
        quiz.value = [];
        score.value = 0;
    };

    return {
        data,
        quiz,
        score,
        startQuiz,
        submitQuiz,
        startOver,
        reset
    };
});
