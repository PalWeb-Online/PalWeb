<script setup>
import TermItem from "./TermItem.vue";

defineProps({
    root: Object,
});
</script>
<template>
    <div class="term-container">
        <div class="term-container-head">
            <div class="term-headword">
                <div class="term-headword-term">
                    <div class="term-headword-arb">{{ root.ar }}</div>
                    <div class="term-headword-eng">({{ root.en }})</div>
                </div>
            </div>
        </div>
        <div v-if="Object.keys(root.terms).length > 0" class=terms-list>
            <template v-for="term in root.terms" :key="term.id">
                <TermItem :model="term"/>
            </template>
        </div>
    </div>
</template>
