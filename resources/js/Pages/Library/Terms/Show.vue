<script setup>
import { ref, onMounted, computed } from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";

defineOptions({ layout: Layout });

const terms   = ref([]);
const loading = ref(true);

async function fetchTerm() {
    loading.value = true;
    try {
        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/library/terms/${slug}`);
        const data = await response.json();
        terms.value = data.terms.data ?? [];
    } catch (error) {
        console.error('Failed to fetch term:', error);
        terms.value = [];
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
        <div v-if="loading" class="loading-state">
            <p>Loading...</p>
        </div>
        <template v-else-if="terms.length > 0">
            <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
        </template>
        <template v-else>
            <p>Term not found.</p>
        </template>
    </div>
</template>