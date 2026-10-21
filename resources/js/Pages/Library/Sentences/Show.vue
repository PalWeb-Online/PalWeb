<script setup>
import {onMounted, ref, watch} from "vue";
import {useRouter} from "vue-router";
import SentenceContainer from "../../../components/SentenceContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useSentenceViewer} from "../../../composables/sentences/useSentenceViewer.js";
import AppTip from "../../../components/AppTip.vue";

const router = useRouter();
const resolvedRandomSentenceId = ref(null);

const props = defineProps({
    sentenceId: {
        type: Number,
        default: null,
    },
    random: {
        type: Boolean,
        default: false,
    },
});

const {
    sentence,
    sentenceNotFound,
    isLoadingSentence,
    loadSentence,
    loadRandomSentence,
    reloadSentence,
    reloadRandomSentence,
} = useSentenceViewer();

const openSentence = async (reload = false) => {
    if (props.random) {
        const randomSentence = await (reload ? reloadRandomSentence : loadRandomSentence)();

        if (randomSentence?.id) {
            resolvedRandomSentenceId.value = randomSentence.id;
            await router.replace({ name: 'sentences.show', params: { sentence: randomSentence.id } });
        }

        return;
    }

    await (reload ? reloadSentence : loadSentence)(props.sentenceId);
};

onMounted(() => {
    void openSentence();
});

watch(
    () => [props.sentenceId, props.random],
    ([sentenceId, random]) => {
        if (!random && sentenceId === resolvedRandomSentenceId.value) {
            resolvedRandomSentenceId.value = null;
            return;
        }

        void openSentence(true);
    }
);
</script>

<template>

    <LoadingSpinner v-if="isLoadingSentence"/>
    <AppTip v-else-if="sentenceNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.sentence')}) }}</p>
    </AppTip>
    <div v-else-if="sentence" id="app-body">
        <SentenceContainer :model="sentence"/>
    </div>
</template>
