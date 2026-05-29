<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";

defineOptions({ layout: Layout });

const sentence = ref(null);
const loading  = ref(true);

async function fetchSentence() {
    loading.value = true;
    try {
        const id = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/library/sentences/${id}`);
        const data = await response.json();
        sentence.value = data.sentence;
    } catch (error) {
        console.error('Failed to fetch sentence:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchSentence());
</script>

<template>
    <Head :title="sentence ? `Library: Corpus: ${sentence.sentence}` : 'Library: Corpus'"/>
    <div id="app-body">
        <div v-if="loading" class="loading-state">
            <p>Loading...</p>
        </div>
        <SentenceContainer v-else :model="sentence"/>
    </div>
</template>