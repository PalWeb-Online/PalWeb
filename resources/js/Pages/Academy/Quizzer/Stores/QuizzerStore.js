import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {shuffle} from "lodash";

export const useQuizzerStore = defineStore('QuizzerStore', () => {
    const data = reactive({
        step: 'settings',
        model: null,
        isSaved: false,
    });

    const settings = reactive({
        modelType: null,
        typeInput: false,
        options: {
            allGlosses: false,
            anyGloss: false,
        }
    });

    const score = ref(0);
    const results = ref({});

    const quiz = ref({});

    const startQuiz = () => {
        generateQuiz().then(() => {
            data.step = 'quiz';
        });

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const generateQuiz = async () => {
        try {
            const response = await axios.get(route('quizzer.deck.quiz', data.model), {
                params: {settings: settings}
            });

            quiz.value = response.data.quiz;

            if (!settings.typeInput) {
                quiz.value.forEach(question => {
                    shuffle(Object.entries(question.options));
                });
            }

            quiz.value = shuffle(quiz.value);

        } catch (error) {
            console.error('Failed to generate quiz', error);
        }
    };

    const submitQuiz = () => {
        results.value = quiz.value;

        if (!settings.typeInput) {
            results.value = results.value.map(q => ({
                term: {
                    term: q.term.term,
                    slug: q.term.slug
                },
                answer: [q.options[q.answer]],
                response: q.options[q.response],
                correct: q.answer === q.response,
            }))

        } else {
            results.value = quiz.value.map(q => ({
                term: {
                    term: q.term.term,
                    slug: q.term.slug
                },
                answer: q.answer,
                response: q.response,
                correct: q.answer.includes(q.response),
            }))
        }

        scoreQuiz();
        data.step = 'results';

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const scoreQuiz = () => {
        score.value = results.value.filter(q => q.correct).length / quiz.value.length;
    };

    const saveScore = async () => {
        await axios.post(route('scores.store'), {
            scorable_id: data.model.id,
            settings: settings,
            score: score.value,
            results: results.value,
        })

        data.isSaved = true;
    };

    const reset = () => {
        data.step = 'settings';
        data.model = null;
        data.isSaved = false;

        settings.modelType = null;
        settings.typeInput = false;
        settings.options = {
            allGlosses: false,
            anyGloss: false,
        }

        score.value = 0;
        quiz.value = [];
    };

    return {
        data,
        settings,
        score,
        results,
        quiz,
        startQuiz,
        submitQuiz,
        scoreQuiz,
        saveScore,
        reset
    };
});
