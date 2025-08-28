import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {shuffle} from "lodash";

export const useQuizzerStore = defineStore('QuizzerStore', () => {
    const data = reactive({
        step: 'setup',
        quizType: 'deck',
        model: null,
        isSaved: false,
    });

    const quiz = ref({});
    const score = ref(0);

    const startQuiz = (quizSettings) => {
        generateQuiz(quizSettings).then(() => {
            data.step = 'quiz';
        });

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const generateQuiz = async (quizSettings) => {
        try {
            const response = await axios.get(route('quizzer.deck.quiz', data.model), {
                params: {settings: quizSettings}
            });

            quiz.value = response.data.quiz;

            quiz.value.questions.forEach(question => {
                shuffle(question.options);
            });

            quiz.value.questions = shuffle(quiz.value.questions);

        } catch (error) {
            console.error('Failed to generate quiz', error);
        }
    };

    const submitQuiz = () => {
        scoreQuiz();
        data.step = 'results';

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const scoreQuiz = () => {
        score.value = 0;
        quiz.value.questions.forEach(item => {
            if (quiz.value.type === 'select') {
                if (item.selection === item.answer) {
                    score.value += 1;
                }

            } else if (quiz.value.type === 'input') {
                if (item.answer.includes(item.input)) {
                    score.value += 1;
                }
            }
        });
    };

    const saveScore = () => {
        console.log(JSON.stringify({quiz: quiz.value}));
        data.isSaved = true;
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
        reset
    };
});
