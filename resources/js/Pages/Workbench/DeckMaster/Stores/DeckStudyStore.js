import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {shuffle} from "lodash";
import {router} from "@inertiajs/vue3";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";

export const useDeckStudyStore = defineStore('DeckStudyStore', () => {
    const NotificationStore = useNotificationStore();

    const data = reactive({
        step: 'settings',
        deck: null,
        terms: [],
        isSaved: false,
        isLoading: false,
    });

    const settings = reactive({
        quizType: 'practice',
        options: {
            strictTerms: true,
            strictGloss: true,
            withTranslation: true,
        }
    });

    const score = reactive({
        settings: {},
        score: 0,
        results: {},
    });

    const quiz = ref([]);

    const startPractice = async () => {
        data.step = 'practice';
        data.isLoading = true;

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        const response = await axios.get(route('deck-master.get-cards', data.deck.id));
        data.terms = response.data.terms;

        data.isLoading = false;
    }

    const startQuiz = () => {
        data.step = 'quiz';
        data.isLoading = true;

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        generateQuiz().then(() => {
            if (!quiz.value.length) {
                NotificationStore.addNotification('No Terms in the Deck could be used to generate the Quiz.', 'error');
                data.step = 'settings';
            }
        });

        data.isLoading = false;
    };

    const generateQuiz = async () => {
        try {
            const response = await axios.post(route('deck-master.get-quiz', data.deck.id), {
                settings: settings
            });

            quiz.value = response.data.quiz;

            if (settings.quizType === 'glosses') {
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

        if (settings.quizType === 'glosses') {
            score.results = score.results.map(q => ({
                term: {
                    id: q.term.id,
                    term: q.term.term,
                    slug: q.term.slug
                },
                answer: [q.options[q.answer]],
                response: q.options[q.response],
                correct: q.answer === q.response,
            }))

        } else if (settings.quizType === 'inflections') {
            score.results = quiz.value.map(q => ({
                term: {
                    id: q.term.id,
                    term: q.term.term,
                    slug: q.term.slug
                },
                prompt: q.prompt,
                answer: q.answer,
                response: q.response,
                correct: q.answer.includes(q.response),
            }))

        } else if (settings.quizType === 'sentences') {
            score.results = quiz.value.map(q => ({
                term: {
                    id: q.term.id,
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
            scorable_type: 'deck',
            scorable_id: data.deck.id,
            settings: score.settings,
            score: score.score,
            results: score.results,
        }, {
            onSuccess: () => {
                data.step = 'settings';
                score.score = 0;
                score.results = {};
                quiz.value = [];
                data.isSaved = false;
            },
            onError: (errors) => {
                data.isSaved = false;
                console.error('Error saving score:', errors);
            }
        });
    };

    const reset = () => {
        data.step = 'settings';
        data.deck = null;
        data.isSaved = false;

        settings.quizType = 'practice';
        settings.options = {
            strictTerms: true,
            strictGloss: true,
            withTranslation: true,
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
        startPractice,
        startQuiz,
        submitQuiz,
        scoreQuiz,
        saveScore,
        reset
    };
});
