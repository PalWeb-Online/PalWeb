<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

defineOptions({ layout: Layout });

const sentence = ref(null);
const loading  = ref(true);
const error = ref(false);

async function fetchSentence() {
    loading.value = true;
    error.value = false;
    try {
        const id = window.location.pathname.split('/').pop();
        const response = await fetch(route('api.sentences.show', id));
        const data = await response.json();
        sentence.value = data.sentence;
    } catch (error) {
        console.error('Failed to fetch sentence:', error);
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchSentence());
</script>

<template>
    <Head :title="sentence ? `Library: Corpus: ${sentence.sentence}` : 'Library: Corpus'"/>
    <div id="app-body">
        <LoadingSpinner v-if="loading"/>
        <SentenceContainer v-else-if="sentence" :model="sentence"/>
        <div v-else-if="error" class="loading-state"><p>Unable to load Sentence.</p></div>
    </div>
</template>
