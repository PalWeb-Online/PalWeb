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
            const result = {
                id: q.id || `q_${q.term.id}_${Math.random().toString(16).slice(2, 6)}`,
                term: {
                    id: q.term.id,
                    term: q.term.term,
                    slug: q.term.slug,
                },
            };

            if (settings.quizType === 'glosses') {
                    result.answer = [q.options[q.answer]];
                    result.response = q.options[q.response];
                    result.correct = q.answer === q.response;

            } else if (settings.quizType === 'inflections') {
                    result.prompt = q.prompt;
                    result.answer = q.answer;
                    result.response = q.response;
                    result.correct = q.answer.includes(q.response);

            } else if (settings.quizType === 'sentences') {
                    result.sentence = {
                        id: q.sentence.id,
                        sentence: q.sentence.sentence,
                    };
                    result.prompt = q.prompt;
                    result.answer = [q.options[q.answer]['term']];
                    result.response = q.options[q.response]['term'];
                    result.correct = q.answer === q.response;
            }

            return result;
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
