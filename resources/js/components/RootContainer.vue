<script setup>
import TermItem from "./TermItem.vue";
import {route} from "ziggy-js";

defineProps({
    root: Object,
});
</script>
<template>
    <div class="window-container">
        <div class="window-header">
            <Link :href="route('terms.index')" class="material-symbols-rounded">home</Link>
            <div class="window-header-url">www.palweb.app/library/roots/{root}</div>
        </div>
        <div class="window-section-head">
            <h1>root</h1>
        </div>
        <div class="term-container-head">
            <div class="term-headword">
                <div class="term-headword-term">
                    <div class="term-headword-arb">{{ root.ar }}</div>
                    <div class="term-headword-eng">({{ root.en }})</div>
                </div>
            </div>
        </div>
        <div class="window-section-head">
            <h2>terms</h2>
        </div>
        <div v-if="Object.keys(root.terms).length > 0" class="model-list index-list">
            <template v-for="term in root.terms" :key="term.id">
                <TermItem :model="term"/>
            </template>
        </div>
    </div>
</template>
