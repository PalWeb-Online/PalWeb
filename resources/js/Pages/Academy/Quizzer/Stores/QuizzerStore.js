import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {shuffle} from "lodash";

export const useQuizzerStore = defineStore('QuizzerStore', () => {
    const data = reactive({
        step: 'setup',
        quizType: '',
        model: null,
        isSaved: false,
    });

    const quiz = ref([]);
    const score = ref(0);

    const startQuiz = (quizSettings) => {
        generateQuiz(quizSettings).then(() => {
            data.step = 'quiz';
        });
    };

    const generateQuiz = async (quizSettings) => {
        try {
            const response = await axios.get(route('quizzer.deck.quiz', data.model), {
                params: {settings: quizSettings}
            });

            quiz.value = response.data.quiz;

            quiz.value.forEach(question => {
                shuffle(Object.entries(question.options));
            });

            quiz.value = shuffle(quiz.value);

        } catch (error) {
            console.error('Failed to generate quiz', error);
        }
    };

    const submitQuiz = () => {
        scoreQuiz();
        data.step = 'results';

        nextTick(() => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    };

    const scoreQuiz = () => {
        score.value = 0;
        quiz.value.forEach(item => {
            if (item.selection === item.answer) {
                score.value += 1;
            }
        });
    };

    const saveScore = () => {
        console.log(JSON.stringify({ quiz: quiz.value }));
        data.isSaved = true;
    };

    const startOver = () => {
        data.step = 'setup';
        data.isSaved = false;
        quiz.value = [];
        score.value = 0;
    };

    const reset = () => {
        data.step = 'setup';
        data.quizType = '';
        data.model = null;
        data.isSaved = false;
        quiz.value = [];
        score.value = 0;
    };

    return {
        data,
        quiz,
        score,
        startQuiz,
        submitQuiz,
        scoreQuiz,
        saveScore,
        startOver,
        reset
    };
});
