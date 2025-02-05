<script setup>
import {Inertia} from "@inertiajs/inertia";
import {Link} from '@inertiajs/inertia-vue3'
import Layout from "../../Shared/Layout.vue";
import TermItem from "../../components/TermItem.vue";
import SentenceContainer from "../../components/SentenceContainer.vue";

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
            <div id="paginator">
                <div class="pagination">
                    <Link v-for="link in terms.meta.links" :key="link.url" :href="link.url"
                          :class="{ active: link.active, disabled: !link.url }"
                          preserve-scroll
                    >
                        <span v-if="link.label.includes('Previous')" class="arrow">&laquo;</span>
                        <span v-else-if="link.label.includes('Next')" class="arrow">&raquo;</span>
                        <span v-else v-html="link.label"></span>
                    </Link>
                </div>
            </div>
        </div>
        <div class="deck-container" v-if="mode === 'sentences'">
            <div class="sentences-list">
                <SentenceContainer v-for="sentence in sentences.data" :key="sentence.id" :model="sentence" size="s"/>
            </div>
            <div id="paginator">
                <div class="pagination">
                    <Link v-for="link in sentences.meta.links" :key="link.id"
                          :href="link.url"
                    >
                        <span v-if="link.label.includes('&laquo;')" class="arrow">&larr;</span>
                        <span v-else-if="link.label.includes('&raquo;')" class="arrow">&rarr;</span>
                        <span v-else v-html="link.label"></span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
