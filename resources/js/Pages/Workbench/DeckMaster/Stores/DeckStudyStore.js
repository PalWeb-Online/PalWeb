import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {shuffle} from "lodash";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useScoreManager} from "../../../../composables/useScoreManager.js";

export const useDeckStudyStore = defineStore('DeckStudyStore', () => {
    const scoreManager = useScoreManager();
    const NotificationStore = useNotificationStore();

    const data = reactive({
        step: 'settings',
        deck: null,
        terms: [],
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
        scoreManager.score.settings = settings;

        scoreManager.score.results = quiz.value.map(q => {
            if (settings.quizType === 'glosses') {
                return {
                    term: {
                        id: q.term.id,
                        term: q.term.term,
                        slug: q.term.slug
                    },
                    answer: [q.options[q.answer]],
                    response: q.options[q.response],
                    correct: q.answer === q.response,
                }

            } else if (settings.quizType === 'inflections') {
                return {
                    term: {
                        id: q.term.id,
                        term: q.term.term,
                        slug: q.term.slug
                    },
                    prompt: q.prompt,
                    answer: q.answer,
                    response: q.response,
                    correct: q.answer.includes(q.response),
                }

            } else if (settings.quizType === 'sentences') {
                return {
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
                }
            }
        });

        scoreManager.calculateScore();
        data.step = 'results';

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const saveScore = () => {
        scoreManager.saveScore('deck', data.deck.id, {
            onSuccess: () => {
                data.step = 'settings';
                quiz.value = [];
            }
        });
    };

    const reset = () => {
        data.step = 'settings';
        data.deck = null;
        settings.quizType = 'practice';
        settings.options = {
            strictTerms: true,
            strictGloss: true,
            withTranslation: true,
        }
        quiz.value = [];
        scoreManager.resetScore();
    };

    return {
        data,
        settings,
        score: scoreManager.score,
        isSaved: scoreManager.isSaved,
        quiz,
        startPractice,
        startQuiz,
        submitQuiz,
        markCorrect: scoreManager.markCorrect,
        saveScore,
        reset
    };
});
