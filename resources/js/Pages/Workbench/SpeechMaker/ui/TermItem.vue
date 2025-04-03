<script setup>
const props = defineProps({
    term: Object,
});
</script>

<template>
    <div class="term-item-wrapper">
        <div class="term-item">
            <div class="term-item-head">
                <input class="arb" v-model="term.sentencePivot.sent_term"/>
                <input class="translit" v-model="term.sentencePivot.sent_translit"/>
            </div>
            <div class="term-item-body">
                <template v-if="term.glosses">
                    <select class="eng" v-model="term.sentencePivot.gloss_id">
                        <option v-for="gloss in term.glosses" :value="gloss.id">
                            {{ gloss.gloss.length > 85 ? gloss.gloss.slice(0, 85) + "..." : gloss.gloss }}
                        </option>
                    </select>
                </template>
            </div>
        </div>
    </div>
</template>
