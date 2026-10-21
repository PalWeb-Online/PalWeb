<script setup>
import {onMounted, ref} from "vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import {route} from "ziggy-js";
import {useRouter} from "vue-router";
import AppTip from "../../../components/AppTip.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import QuizzerWindow from "./UI/QuizzerWindow.vue";
import ScoreStats from "../../../components/ScoreStats.vue";
import ReviewProgress from "../CardDealer/UI/ReviewProgress.vue";

const router = useRouter();

const props = defineProps({
    mode: {type: String, default: 'build'},
});

const mode = ref(props.mode);
const decks = ref([]);
const selectedDeck = ref(null);
const collapsed = ref(true);

const toggleMode = async () => {
    selectedDeck.value = null;
    mode.value = mode.value === 'build' ? 'study' : 'build';
    await fetchDecks(mode.value);
}

const isLoading = ref(false);

const fetchDecks = async (mode) => {
    isLoading.value = true;
    decks.value = [];

    try {
        const response = await axios.get(route('deck-master.get-decks'), {
            params: {mode}
        });

        decks.value = response.data.decks;

    } catch (error) {
        console.error("Failed to fetch decks", error);
    }

    isLoading.value = false;
};

const toggleSelectDeck = (index) => {
    selectedDeck.value =
        selectedDeck.value?.id === decks.value[index].id
            ? null
            : decks.value[index];
};

const toBuild = () => {
    selectedDeck.value?.id
        ? router.push({name: 'deck-master.build', params: {deck: selectedDeck.value?.id}})
        : router.push({name: 'deck-master.build'})
};

const toStudy = () => {
    if (selectedDeck.value?.id) {
        router.push({
            name: 'deck-master.study',
            params: {deck: selectedDeck.value.id},
        });
    }
};

onMounted(async () => {
    await fetchDecks(props.mode);
    selectedDeck.value = props.deck;
});
</script>

<template>
    <div id="app-head">
        <h1>{{ $t('pages.deck-master.title') }}</h1>
        <div @click="toggleMode" class="app-mode-toggle" :class="mode">
            <div class="app-mode-toggle-slider" :class="{active: mode === 'study'}">
                {{ $t('pages.deck-master.' + mode) }}
            </div>
        </div>
    </div>

    <div id="app-body" :class="{ 'dm-mode-study': mode === 'study' }">
        <div id="dm-select">
            <div class="deck-item-grid-wrapper">
                <button v-if="mode === 'build'" class="material-symbols-rounded" @click="toBuild">
                    {{ selectedDeck ? 'edit' : 'add' }}
                </button>
                <button v-else-if="mode === 'study'" class="material-symbols-rounded" @click="collapsed = !collapsed">
                    {{ !collapsed ? 'collapse_all' : 'expand_all' }}
                </button>

                <div v-if="!isLoading && decks.length" class="deck-item-grid"
                     :class="{ collapsed: mode === 'study' && collapsed }">
                    <DeckFlashcard v-for="(deck, index) in decks" :key="deck.id" :model="deck"
                                   :disabled="mode === 'study' && !deck.terms.length"
                                   :active="selectedDeck?.id === deck.id"
                                   @flip="toggleSelectDeck(index)"
                    />
                </div>
                <div v-else-if="!isLoading && !decks.length" class="deck-item-grid">
                    <AppTip>
                        <p v-if="mode === 'build'">
                            {{ $t('pages.deck-master.messages.no-created-decks') }}
                        </p>
                        <p v-if="mode === 'study'">
                            {{ $t('pages.deck-master.messages.no-pinned-decks') }}
                        </p>
                    </AppTip>
                </div>
                <div v-show="isLoading" class="deck-item-grid">
                    <LoadingSpinner/>
                </div>
            </div>
        </div>

        <QuizzerWindow v-if="mode === 'study'" :deck="selectedDeck" step="select">
            <ReviewProgress
                :cards="selectedDeck?.terms.filter(term => term.card).flatMap(term => term.card) ?? []"
                :terms_count="selectedDeck?.terms.length ?? 0"
            />
            <ScoreStats :model="selectedDeck"/>
            <div v-if="selectedDeck" class="window-footer">
                <button @click="toStudy">
                    {{ $t('pages.deck-master.select-deck') }}
                </button>
            </div>
        </QuizzerWindow>
    </div>
</template>
