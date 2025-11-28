<script setup>
const props = defineProps({
    term: Object,
});
</script>

<template>
    <div class="model-item-container term-item-container">
        <div class="model-item term-item">
            <div class="model-item-content">
                <div class="term-item-gloss">
                    <select v-model="term.deckPivot.gloss_id">
                        <option v-for="gloss in term.glosses" :value="gloss.id">
                            {{ gloss.gloss.length > 85 ? gloss.gloss.slice(0, 85) + "..." : gloss.gloss }}
                        </option>
                    </select>
                </div>
                <div class="term-item-term">
                    <div style="height: 100%; overflow: scroll; display: flex; align-items: center; gap: 1.2rem;">
                        <span class="arb">{{ term.term }}</span>
                        <span class="translit">{{ term.translit }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
