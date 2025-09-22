import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {shuffle} from "lodash";
import {router} from "@inertiajs/vue3";

export const useQuizzerStore = defineStore('QuizzerStore', () => {
    const data = reactive({
        step: 'settings',
        model: null,
        scorable_type: null,
        isSaved: false,
        isLoading: false,
    });

    const settings = reactive({
        quizType: '',
        options: {
            allGlosses: false,
            anyGloss: false,
            withPrompt: true,
        }
    });

    const score = reactive({
        settings: {},
        score: 0,
        results: {},
    });

    const quiz = ref({});

    const startQuiz = () => {
        data.step = 'quiz';
        data.isLoading = true;

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        generateQuiz().then(() => {
            data.isLoading = false;
        });
    };

    const generateQuiz = async () => {
        try {
            const response = await axios.post(route('quizzer.generate', {
                scorable_type: data.scorable_type,
                scorable_id: data.model.id
            }), {
                settings: settings
            });

            quiz.value = response.data.quiz;

            if (settings.quizType === 'term-gloss') {
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
        score.settings = settings;
        score.results = quiz.value;

        if (settings.quizType === 'term-gloss') {
            score.results = score.results.map(q => ({
                term: {
                    term: q.term.term,
                    slug: q.term.slug
                },
                answer: [q.options[q.answer]],
                response: q.options[q.response],
                correct: q.answer === q.response,
            }))

        } else if (settings.quizType === 'term-inflection') {
            score.results = quiz.value.map(q => ({
                term: {
                    term: q.term.term,
                    slug: q.term.slug
                },
                prompt: q.prompt,
                answer: q.answer,
                response: q.response,
                correct: q.answer.includes(q.response),
            }))

        } else if (settings.quizType === 'sentence-term') {
            score.results = quiz.value.map(q => ({
                term: {
                    term: q.term.term,
                    slug: q.term.slug
                },
                sentence: {
                    id: q.sentence.id,
                    sentence: q.sentence.sentence,
                },
                prompt: q.prompt,
                answer: [q.options[q.answer]['term']],
                response: q.options[q.response]['term'],
                correct: q.answer === q.response,
            }))
        }

        scoreQuiz();
        data.step = 'results';

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const scoreQuiz = () => {
        score.score = score.results.filter(q => q.correct).length / quiz.value.length;
    };

    const saveScore = async () => {
        data.isSaved = true;

        router.post(route('scores.store'), {
            scorable_type: data.scorable_type,
            scorable_id: data.model.id,
            settings: score.settings,
            score: score.score,
            results: score.results,
        }, {
            onError: (errors) => {
                data.isSaved = false;
                console.error('Error saving score:', errors);
            }
        });
    };

    const reset = () => {
        data.step = 'settings';
        data.model = null;
        data.scorable_type = null;
        data.isSaved = false;

        settings.quizType = null;
        settings.options = {
            allGlosses: false,
            anyGloss: false,
            withPrompt: true,
        }

        score.score = 0;
        score.results = {};

        quiz.value = [];
    };

    return {
        data,
        settings,
        score,
        quiz,
        startQuiz,
        submitQuiz,
        scoreQuiz,
        saveScore,
        reset
    };
});
