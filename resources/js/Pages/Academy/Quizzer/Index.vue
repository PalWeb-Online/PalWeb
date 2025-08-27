<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {ref} from "vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import {router} from "@inertiajs/vue3";
import PinButton from "../../../components/PinButton.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import ScoreStats from "../../../components/ScoreStats.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Array
});

const selectedType = ref('');
const selectedModel = ref(null);

const selectModel = (quizType, index) => {
    if (selectedModel.value?.id === props.decks[index].id) {
        selectedType.value = null;
        selectedModel.value = null;

    } else {
        selectedType.value = quizType;
        selectedModel.value = props.decks[index];
    }
};
</script>
<template>
    <Head title="Academy: Quizzer"/>
    <div id="app-head">
        <Link :href="route('quizzer.index')"><h1>Quizzer</h1></Link>
    </div>
    <div id="app-body">
        <div id="dm-select">
            <div class="deck-item-grid">
                <DeckFlashcard v-for="(deck, index) in decks" :key="deck.id" :model="deck"
                               :disabled="!deck.terms.length"
                               :active="selectedModel?.id === deck.id"
                               @flip="selectModel('deck', index)"
                />
            </div>
        </div>

        <div class="window-container">
            <div class="window-header">
                <button @click="() => {selectedType = null; selectedModel = null}"
                        class="material-symbols-rounded">close
                </button>
                <div class="window-header-url">www.palweb.app/academy/quizzer</div>
            </div>
            <div class="window-section-head">
                <h1>Deck</h1>
                <template v-if="selectedModel">
                    <PinButton modelType="deck" :model="selectedModel"/>
                    <DeckActions :model="selectedModel"/>
                </template>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title">{{ selectedModel?.name }}</div>
            </div>
            <ScoreStats :model="false"/>
            <div v-if="selectedModel" class="window-footer">
                <button @click="router.get(route(`quizzer.${selectedType}`, selectedModel.id))">Select Deck</button>
            </div>
        </div>
    </div>
</template>
