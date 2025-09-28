<script setup>
const props = defineProps({
    term: Object,
});
</script>

<template>
    <div class="term-item-wrapper">
        <div class="term-item">
            <div class="term-item-head">
                <div class="arb">{{ term.term }}</div>
                <div class="translit">{{ term.translit }}</div>
            </div>
            <div class="term-item-body">
                <select class="eng" v-model="term.deckPivot.gloss_id">
                    <option v-for="gloss in term.glosses" :value="gloss.id">
                        {{ gloss.gloss.length > 85 ? gloss.gloss.slice(0, 85) + "..." : gloss.gloss }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>
