<script setup>
import Settings from "./Pages/Settings.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onMounted, watch} from "vue";
import Practice from "./Pages/Practice.vue";
import {createDeckStudySession, provideDeckStudySession} from "../../../composables/decks/useDeckStudySession.js";
import {useNotifier} from '../../../composables/useNotifier.js';
import {useRouter} from 'vue-router';
import {useDeckViewer} from "../../../composables/decks/useDeckViewer.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";

const router = useRouter();
const Notifier = useNotifier();
const DeckStudySession = provideDeckStudySession(createDeckStudySession());

const {
    deck,
    deckNotFound,
    isLoadingDeck,
    loadDeck,
    reloadDeck,
} = useDeckViewer();

const props = defineProps({
    deckId: Number,
});

const openDeck = async (loader = loadDeck) => {
    DeckStudySession.reset();
    const deck = await loader(props.deckId);

    if (!deck) return;

    if (!deck.terms.length) {
        Notifier.notify({
            key: 'pages.deck-master.notifications.empty-deck',
            type: 'warning',
        });

        router.push({ name: 'deck-master.index', query: { mode: 'study' } });

    } else {
        DeckStudySession.data.deck = deck;
    }
};

onMounted(() => {
    void openDeck();
});

watch(() => props.deckId, () => {
    void openDeck(reloadDeck);
});
</script>
<template>
    <LoadingSpinner v-if="isLoadingDeck"/>
    <AppTip v-else-if="deckNotFound">
        <p>{{ $t('pages.common.not-found', { model: $t('actions.models.deck') }) }}</p>
    </AppTip>
    <div v-else-if="deck" id="app-body">
        <Settings v-if="DeckStudySession.data.step === 'settings'"/>
        <Practice v-if="DeckStudySession.data.step === 'practice'"/>
        <Quiz v-if="DeckStudySession.data.step === 'quiz'"/>
        <Results v-if="DeckStudySession.data.step === 'results'"/>
    </div>
</template>
