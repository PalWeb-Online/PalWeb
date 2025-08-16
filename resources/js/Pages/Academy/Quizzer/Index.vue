<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {ref} from "vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import AppButton from "../../../components/AppButton.vue";
import {router} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Array
});

const selectedType = ref('');
const selectedModel = ref(null);

const selectModel = (quizType, index) => {
    console.log(quizType, index);
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
            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <AppButton v-if="selectedModel"
                               @click="router.get(route(`quizzer.${selectedType}`, selectedModel.id))"
                               label="quiz"/>
                    <AppButton v-else label="quiz" disabled/>
                </div>
            </div>
            <div class="deck-item-grid">
                <DeckFlashcard v-for="(deck, index) in decks" :key="deck.id" :model="deck"
                               :disabled="!deck.terms.length"
                               :active="selectedModel?.id === deck.id"
                               @flip="selectModel('deck', index)"
                />
            </div>
        </div>
    </div>
</template>
