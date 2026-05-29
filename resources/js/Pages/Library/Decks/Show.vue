<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";

defineOptions({ layout: Layout });

const deck    = ref(null);
const loading = ref(true);
const error   = ref(false);

async function fetchDeck() {
    loading.value = true;
    error.value = false;
    try {
        const id = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/library/decks/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'include',
        });

        if (!response.ok) {
            error.value = true;
            return;
        }

        const data = await response.json();
        deck.value = data.deck;
    } catch (err) {
        console.error('Failed to fetch deck:', err);
        error.value = true;
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
        <div v-else-if="error" class="loading-state">
            <p>Unable to load deck. Please log in.</p>
        </div>
        <DeckContainer v-else-if="deck" :model="deck"/>
    </div>
</template>