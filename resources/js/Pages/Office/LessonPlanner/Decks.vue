<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import AppTip from "../../../components/AppTip.vue";
import {route} from "ziggy-js";
import {computed, reactive, ref} from "vue";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    unit: {
        type: Object,
        required: true,
    },
    decks: {
        type: Array,
        default: () => [],
    },
})

const sortMode = ref('deck');

const deckGroups = computed(() => props.decks.map((deck) => ({
    key: `deck-${deck.id}`,
    count: deck.terms.length,
    heading: deck.lesson.global_position,
    emptyMessage: 'No terms in this Deck.',
    terms: deck.terms,
})));

const categoryGroups = computed(() => {
    const groups = new Map();

    props.decks.forEach((deck) => {
        deck.terms.forEach((term) => {
            const category = term.category || 'Uncategorized';

            if (!groups.has(category)) {
                groups.set(category, []);
            }

            groups.get(category).push(term);
        });
    });

    return Array.from(groups.entries())
        .sort(([a], [b]) => a.localeCompare(b))
        .map(([category, terms]) => ({
            key: `category-${category}`,
            count: terms.length,
            heading: category,
            emptyMessage: 'No terms in this Category.',
            terms,
        }));
});

const groups = computed(() => sortMode.value === 'deck'
    ? deckGroups.value
    : categoryGroups.value
);

const openGroups = reactive({});

const toggleGroup = (key) => {
    openGroups[key] = !openGroups[key];
};

const isGroupOpen = (key) => Boolean(openGroups[key]);
</script>

<template>
    <Head :title="`Lesson Planner: Unit ${unit.position} Decks`"/>

    <div id="app-head">
        <h1>{{ $t('pages.lesson-planner.title') }}</h1>
    </div>

    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%); padding: 0">
            <div class="unit-meta">
                <Link :href="route('lesson-planner.unit', unit.id)">
                    <- to Unit
                </Link>
            </div>

            <div class="featured-title l">
                {{ $t('unit.number', {number: unit.position}) }}: {{ unit.title }}
            </div>

            <div v-if="decks.length" class="unit-decks-sort-toggle" role="group" aria-label="Sort terms">
                <button
                    type="button"
                    class="app-button"
                    :class="{ active: sortMode === 'deck' }"
                    @click="sortMode = 'deck'"
                >
                    Deck
                </button>
                <button
                    type="button"
                    class="app-button"
                    :class="{ active: sortMode === 'category' }"
                    @click="sortMode = 'category'"
                >
                    Category
                </button>
            </div>

            <template v-if="decks.length">
                <template v-if="groups.length">
                    <section v-for="group in groups" :key="group.key" class="app-collapsible">
                        <div class="app-collapsible-head">
                            <button class="material-symbols-rounded" @click="toggleGroup(group.key)">
                                {{ isGroupOpen(group.key) ? 'collapse_content' : 'expand_content' }}
                            </button>
                            <div class="featured-title m">
                                {{ group.heading }}
                                <span style="font-size: 3.6rem; color: var(--color-medium-primary)">
                                    {{ group.count }}
                                </span>
                            </div>
                        </div>

                        <template v-if="isGroupOpen(group.key)">
                            <div v-if="group.terms.length" class="model-list">
                                <TermItem
                                    v-for="(term, index) in group.terms"
                                    :key="`${group.key}-${term.id}-${index}`"
                                    :model="term"
                                    :gloss-id="term.deckPivot?.gloss_id"
                                />
                            </div>
                            <AppTip v-else>
                                <p>{{ group.emptyMessage }}</p>
                            </AppTip>
                        </template>
                    </section>
                </template>

                <AppTip v-else>
                    <p>No Terms found for this Unit.</p>
                </AppTip>
            </template>

            <AppTip v-else>
                <p>No Decks found for this Unit.</p>
            </AppTip>
        </div>
    </div>
</template>

<style scoped lang="scss">
.unit-decks-sort-toggle {
    display: flex;
    justify-content: space-between;
    gap: 0.8rem;
}

// todo: extract & use with Container Block

.app-collapsible {
    margin-top: 3.2rem;
    border-radius: 1.6rem;
    background: var(--color-pastel-medium);

    .model-list {
        padding: 0.8rem 2.4rem 3.2rem;
    }
}

.app-collapsible-head {
    justify-self: start;
    display: flex;
    align-items: center;
    gap: 1.6rem;
    margin: 0 1.6rem -1.6rem;
    transform: translateY(-2.4rem);
    user-select: none;

    .material-symbols-rounded {
        height: 3.2rem;
        width: 3.2rem;
        border-radius: 50%;
        color: white;
        background: var(--color-medium-primary);
        font-size: 2.4rem;
    }

    h2 {
        font-family: var(--head-font), serif;
        font-weight: 700;
        font-size: 3.2rem;
        color: var(--color-dark-primary);
        text-transform: none;
        margin: 0
    }

    & + * {
        margin-block-start: 0.8rem;
    }
}
</style>
