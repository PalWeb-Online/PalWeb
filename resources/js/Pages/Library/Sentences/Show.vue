<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useSentenceViewer} from "../../../composables/sentences/useSentenceViewer.js";

defineOptions({layout: Layout});

const props = defineProps({
    sentenceId: {
        type: Number,
        required: true,
    },
});

const {
    sentence,
    sentenceNotFound,
    isLoadingSentence,
    loadSentence,
    reloadSentence,
} = useSentenceViewer();

onMounted(() => loadSentence(props.sentenceId));

watch(
    () => props.sentenceId,
    () => reloadSentence(props.sentenceId)
);
</script>

<template>
    <Head :title="sentence ? `Library: Corpus: ${sentence.sentence}` : 'Library: Corpus'"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingSentence"/>
        <SentenceContainer v-else-if="sentence" :model="sentence"/>
        <div v-else-if="sentenceNotFound" class="loading-state"><p>Unable to load Sentence.</p></div>
    </div>
</template>
