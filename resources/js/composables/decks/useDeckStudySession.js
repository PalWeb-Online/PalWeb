import {inject, nextTick, provide, reactive, ref} from "vue";
import {shuffle} from "lodash";
import {useScoreManager} from "../useScoreManager.js";
import {useNotifier} from "../useNotifier.js";

const deckStudySessionKey = Symbol("DeckStudySession");

export function createDeckStudySession() {
    const scoreManager = useScoreManager();
    const Notifier = useNotifier();

    const data = reactive({
        step: 'settings',
        deck: null,
        terms: [],
        isLoading: false,
    });

    const settings = reactive({
        quizType: 'practice',
        options: {
            promptTerm: true,
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

        const response = await axios.get(`/api/deck-master/decks/${data.deck.id}/cards`);
        data.terms = response.data.terms;

        data.isLoading = false;
    };

    const startQuiz = async () => {
        scoreManager.score.scorable_type = 'deck';
        scoreManager.isSaved.value = false;

        data.step = 'quiz';
        data.isLoading = true;

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        try {
            await generateQuiz();

            if (quiz.value.length < 5) {
                Notifier.notify({
                    key: 'pages.deck-master.notifications.insufficient-items',
                    type: 'warning'
                });

                data.step = 'settings';
            }

        } catch (e) {
            Notifier.notify({
                key: 'pages.deck-master.notifications.quiz-generation-error',
                type: 'error'
            });

            data.step = 'settings';

        } finally {
            data.isLoading = false;
        }
    };

    const generateQuiz = async () => {
        const response = await axios.post(`/api/deck-master/decks/${data.deck.id}/quiz`, {
            settings: settings
        });

        quiz.value = response.data.quiz;

        if (settings.quizType === 'glosses') {
            quiz.value.forEach(question => {
                shuffle(Object.entries(question.options));
            });
        }

        quiz.value = shuffle(quiz.value);
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
        scoreManager.saveScore(data.deck.id, {
            onSuccess: () => {
                data.step = 'settings';
                quiz.value = [];
            }
        });
    };

    const reset = () => {
        data.step = 'settings';
        data.deck = null;
        data.terms = [];
        data.isLoading = false;
        settings.quizType = 'practice';
        settings.options = {
            promptTerm: true,
            strictTerms: true,
            strictGloss: true,
            withTranslation: true,
        };
        quiz.value = [];
        scoreManager.resetScore();
    };

    return reactive({
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
        reset,
    });
}

export function provideDeckStudySession(session) {
    provide(deckStudySessionKey, session);
    return session;
}

export function injectDeckStudySession() {
    return inject(deckStudySessionKey, null);
}

export function useDeckStudySession() {
    const session = injectDeckStudySession();

    if (!session) {
        throw new Error("useDeckStudySession must be used inside a Deck Master study page provider.");
    }

    return session;
}
