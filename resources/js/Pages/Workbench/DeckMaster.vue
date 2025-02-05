<script setup>
import {onBeforeUnmount, onMounted} from "vue";
import {useStateStore} from "../../components/decks/stores/StateStore.js";
import {useDeckStore} from "../../components/decks/stores/DeckStore.js";
import Select from "../../components/decks/pages/Select.vue";
import Build from "../../components/decks/pages/Build.vue";
import Study from "../../components/decks/pages/Study.vue";
import Layout from "../../Shared/Layout.vue";

const StateStore = useStateStore();
const DeckStore = useDeckStore();

const props = defineProps({
    mode: {type: String, default: 'build'},
    step: {type: String, default: 'select'},
    stagedDeck: Object,
});

const toggleMode = async () => {
    DeckStore.data.stagedDeck = DeckStore.initializeDeck();
    StateStore.data.step = 'select';
    StateStore.data.mode = StateStore.data.mode === 'build' ? 'study' : 'build';
}

onMounted(() => {
    StateStore.data.mode = props.mode;
    StateStore.data.step = props.step;

    if (props.stagedDeck) {
        DeckStore.data.stagedDeck = props.stagedDeck;
    }
});

onBeforeUnmount(() => {
    DeckStore.data.stagedDeck = DeckStore.initializeDeck();
    StateStore.data.mode = 'build';
    StateStore.data.step = 'select';
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Deck Master"/>
    <div id="app-head">
        <h1>Deck Master</h1>
        <div v-if="StateStore.data.step === 'select'" id="app-nav">
            <div @click="toggleMode" id="dm-mode-toggle" :class="StateStore.data.mode">
                <div class="dm-mode-toggle-slider">{{ StateStore.data.mode }}</div>
            </div>
        </div>
    </div>

    <div id="app-body" :class="{ 'dm-mode-study': StateStore.data.mode === 'study' }">
        <div id="dm-select" v-if="StateStore.data.step === 'select'">
            <Select/>
        </div>
        <template v-if="StateStore.data.mode === 'build'">
            <div id="dm-build" v-if="StateStore.data.step === 'build'">
                <Build/>
            </div>
        </template>
        <template v-if="StateStore.data.mode === 'study'">
            <div id="dm-study" v-if="StateStore.data.step === 'study'">
                <Study/>
            </div>
        </template>
    </div>
</template>
