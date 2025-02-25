<script setup>
import {Inertia} from "@inertiajs/inertia";
import Layout from "../../Shared/Layout.vue";
import TermItem from "../../components/TermItem.vue";
import SentenceContainer from "../../components/SentenceContainer.vue";
import Paginator from "../../Shared/Paginator.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    mode: { type: String, default: 'terms' },
    terms: Object,
    sentences: Object,
});

function switchMode(newMode) {
    Inertia.get(route('workbench.index'), { mode: newMode }, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Pin Board"/>
    <div id="app-head">
        <h1>Pin Board</h1>
        <div id="pb-mode-toggle">
            <div class="pb-mode-toggle-item" @click="switchMode('terms')">
                <img :class="mode !== 'terms' ? 'unpinned' : ''"
                     src="/img/pin.svg" alt="Pin"
                />
                <div :class="mode === 'terms' ? 'active' : ''">/terms</div>
            </div>
            <div class="pb-mode-toggle-item" @click="switchMode('sentences')">
                <img :class="mode !== 'sentences' ? 'unpinned' : ''"
                     @click="mode = 'sentences'"
                     src="/img/pin.svg" alt="Pin"
                />
                <div :class="mode === 'sentences' ? 'active' : ''">/sentences</div>
            </div>
        </div>
    </div>

    <div id="app-body">
        <div class="deck-container" v-if="mode === 'terms'">
            <div class="terms-list">
                <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
            </div>
            <Paginator :links="terms.meta.links"/>
        </div>
        <div class="deck-container" v-if="mode === 'sentences'">
            <div class="sentences-list">
                <SentenceContainer v-for="sentence in sentences.data" :key="sentence.id" :model="sentence" size="s"/>
            </div>
            <Paginator :links="sentences.meta.links"/>
        </div>
    </div>
</template>
