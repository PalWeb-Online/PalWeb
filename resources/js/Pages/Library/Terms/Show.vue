<script setup>
import {ref, onMounted, computed} from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

defineOptions({layout: Layout});

const terms = ref([]);
const loading = ref(true);
const error = ref(false);

async function fetchTerm() {
    loading.value = true;
    error.value = false;
    try {
        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(route('api.terms.show', slug));
        const data = await response.json();
        terms.value = data.terms;
    } catch (error) {
        console.error('Failed to fetch model:', error);
        terms.value = [];
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchTerm());

const pageTitle = computed(() => {
    if (terms.value.length > 0) {
        return `Dictionary: ${terms.value[0].term} (${terms.value[0].translit})`;
    }
    return 'Dictionary';
});
</script>

<template>
    <Head :title="pageTitle"/>
    <div id="app-body">
        <LoadingSpinner v-if="loading"/>
        <TermContainer v-else-if="terms.length > 0" v-for="term in terms" :key="term.id" :model="term"/>
        <div v-else-if="error" class="loading-state"><p>Unable to load Term.</p></div>
    </div>
</template>
