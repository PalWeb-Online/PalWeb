<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";

defineOptions({ layout: Layout });

const deck    = ref(null);
const loading = ref(true);

async function fetchDeck() {
    loading.value = true;
    try {
        const id = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/library/decks/${id}`);
        const data = await response.json();
        deck.value = data.deck;
    } catch (error) {
        console.error('Failed to fetch deck:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchDeck());
</script>

<template>
    <Head :title="deck ? `Library: Decks: ${deck.name}` : 'Library: Decks'"/>
    <div id="app-body">
        <div v-if="loading" class="loading-state">
            <p>Loading...</p>
        </div>
        <DeckContainer v-else :model="deck"/>
    </div>
</template>