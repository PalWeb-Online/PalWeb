<script setup>
import {onMounted, computed, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useTermViewer} from "../../../composables/terms/useTermViewer.js";

defineOptions({layout: Layout});

const props = defineProps({
    termId: {
        type: Number,
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

onMounted(() => loadTerms(props.termId));

watch(
    () => props.termId,
    () => reloadTerms(props.termId)
);

const pageTitle = computed(() => {
    if (primaryTerm.value) {
        return `Dictionary: ${primaryTerm.value.term} (${primaryTerm.value.translit})`;
    }
    return 'Dictionary';
});
</script>

<template>
    <Head :title="pageTitle"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingTerms"/>
        <TermContainer v-else-if="terms?.length > 0" v-for="term in terms" :key="term.id" :model="term"/>
        <div v-else-if="termsNotFound" class="loading-state"><p>Unable to load Term.</p></div>
    </div>
</template>
