<script setup>
import {onBeforeUnmount, onMounted} from "vue";
import {useDeckMasterStore} from "./stores/DeckMasterStore.js";
import Layout from "../../../Shared/Layout.vue";
import Select from "./pages/Select.vue";
import Build from "./pages/Build.vue";
import Study from "./pages/Study.vue";

const DeckMasterStore = useDeckMasterStore();

const props = defineProps({
    mode: {type: String, default: 'build'},
    step: {type: String, default: 'select'},
    stagedDeck: Object,
    deckCards: Array,
});

const toggleMode = async () => {
    DeckMasterStore.data.isLoading = true;

    DeckMasterStore.data.activeId = null;
    DeckMasterStore.initializeDeck();
    DeckMasterStore.data.mode = DeckMasterStore.data.mode === 'build' ? 'study' : 'build';
    await DeckMasterStore.fetchDecks(DeckMasterStore.data.mode);

    DeckMasterStore.data.isLoading = false;
}

onMounted(() => {
    DeckMasterStore.data.mode = props.mode;
    DeckMasterStore.data.step = props.step;

    if (props.stagedDeck) {
        DeckMasterStore.data.stagedDeck = props.stagedDeck;
    }

    if (props.deckCards) {
        DeckMasterStore.data.deckCards = props.deckCards;
    }
});

onBeforeUnmount(() => {
    DeckMasterStore.data.decks = [];
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Deck Master"/>
    <div id="app-head">
        <h1>Deck Master</h1>
        <div v-if="DeckMasterStore.data.step === 'select'" id="app-nav">
            <div @click="toggleMode" id="dm-mode-toggle" :class="DeckMasterStore.data.mode">
                <div class="dm-mode-toggle-slider">{{ DeckMasterStore.data.mode }}</div>
            </div>
        </div>
    </div>

    <div id="app-body" :class="{ 'dm-mode-study': DeckMasterStore.data.mode === 'study' }">
        <div id="dm-select" v-if="DeckMasterStore.data.step === 'select'">
            <Select/>
        </div>
        <template v-if="DeckMasterStore.data.mode === 'build'">
            <div id="dm-build" v-if="DeckMasterStore.data.step === 'build'">
                <Build/>
            </div>
        </template>
        <template v-if="DeckMasterStore.data.mode === 'study'">
            <div id="dm-study" v-if="DeckMasterStore.data.step === 'study'">
                <Study/>
            </div>
        </template>
    </div>
</template>
