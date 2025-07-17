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

    return {
        data,
        quiz,
        score,
        startQuiz,
        submitQuiz
    };
});
