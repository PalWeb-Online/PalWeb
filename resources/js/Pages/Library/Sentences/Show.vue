<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useSentenceViewer} from "../../../composables/sentences/useSentenceViewer.js";
import AppTip from "../../../components/AppTip.vue";

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

    <LoadingSpinner v-if="isLoadingSentence"/>
    <AppTip v-else-if="sentenceNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.sentence')}) }}</p>
    </AppTip>
    <div v-else-if="sentence" id="app-body">
        <SentenceContainer :model="sentence"/>
    </div>
</template>
