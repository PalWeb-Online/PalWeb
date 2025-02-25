<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";

defineOptions({
    layout: Layout
});

const props = defineProps({
    terms: Object,
    latestTerms: Object,
    featuredTerm: Object,
    filters: Object,
    letters: Array,
});

const selectedLetter = ref(props.filters.letter || null);

function filterByLetter(letter) {
    const newLetter = selectedLetter.value === letter ? null : letter;
    Inertia.get(window.location.pathname, {letter: newLetter});
}
</script>

<template>
    <Head title="Library: Terms"/>
    <div id="app-head">
        <h1>Terms</h1>
    </div>
    <div id="app-body">
<!--        todo: put in a collapsible instead -->
        <template v-if="filters.length === 0">
            <div class="terms-featured-wrapper">
                <div class="terms-featured-daily">
                    <div class="featured-title l" style="text-transform: none">Word of the Day</div>
                    <TermItem :model="featuredTerm"/>
                </div>

                <div class="terms-featured-latest">
                    <div class="featured-title m" style="text-transform: none">Latest</div>
                    <TermItem v-for="term in latestTerms" :model="term"/>
                </div>
            </div>
        </template>

        <div class="letters-array">
            <button
                v-for="letter in letters"
                :key="letter"
                :class="{ 'active': selectedLetter === letter }"
                @click="filterByLetter(letter)"
            >
                {{ letter }}
            </button>
        </div>

        <div class="deck-container">
            <div class="terms-list">
                <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
            </div>
            <Paginator :links="terms.meta.links"/>
        </div>
    </div>
</template>
