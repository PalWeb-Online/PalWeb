<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";

defineOptions({ layout: Layout });

// ── State ───────────────────────────────────────────────────────────────────
const terms   = ref([]);
const loading = ref(true);

// ── API fetch ────────────────────────────────────────────────────────────────
async function fetchTerm() {
    loading.value = true;
    try {
        const slug = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/library/terms/${slug}`);
        const data = await response.json();
        terms.value = data.terms.data;
    } catch (error) {
        console.error('Failed to fetch term:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchTerm());
</script>

<template>
    <Head :title="terms.length ? `Dictionary: ${terms[0].term} (${terms[0].translit})` : 'Dictionary'"/>
    <div id="app-body">
        <div v-if="loading" class="loading-state">
            <p>Loading...</p>
        </div>
        <template v-else>
            <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
        </template>
    </div>
</template>