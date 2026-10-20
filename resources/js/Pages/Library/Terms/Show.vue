<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useTermViewer} from "../../../composables/terms/useTermViewer.js";
import AppTip from "../../../components/AppTip.vue";

defineOptions({layout: Layout});

const props = defineProps({
    termSlug: {
        type: String,
        required: true,
    },
});

const {
    terms,
    primaryTerm,
    termsNotFound,
    isLoadingTerms,
    loadTerms,
    reloadTerms,
} = useTermViewer();

onMounted(() => loadTerms(props.termSlug));

watch(
    () => props.termSlug,
    () => reloadTerms(props.termSlug)
);

watch(
    primaryTerm,
    (term) => {
        document.title = term
            ? `Dictionary: ${term.term} (${term.translit})`
            : 'Dictionary';
    },
    { immediate: true }
);
</script>

<template>
    <LoadingSpinner v-if="isLoadingTerms"/>
    <AppTip v-else-if="termsNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.term')}) }}</p>
    </AppTip>
    <div v-else-if="terms?.length > 0" id="app-body">
        <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
    </div>
</template>
