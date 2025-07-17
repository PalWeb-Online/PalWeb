<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import DeckItem from "../../../components/DeckItem.vue";
import {ref} from "vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    latest: Object
});

const selectedType = ref('');
const selectedModel = ref(null);

const selectModel = (quizType, model) => {
    selectedType.value = quizType;
    selectedModel.value = model;
};
</script>
<template>
    <Head title="Academy: Quizzer"/>
    <div id="app-head">
        <Link :href="route('dialogs.index')"><h1>Quizzer</h1></Link>
    </div>
    <div id="app-body">
        <div v-if="selectedModel">
            <DeckFlashcard :model="selectedModel"/>
            <Link :href="route(`quizzer.${selectedType}`, selectedModel.id)">Quiz Me</Link>
        </div>
        <div class="model-list" v-for="model in latest" :key="model.id">
            <DeckItem :model="model"/>
            <button @click="selectModel('deck', model)">Select</button>
        </div>
    </div>
</template>
