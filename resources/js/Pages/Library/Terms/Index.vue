<script setup>
import {computed, ref} from "vue";
import TermItem from "../../../components/TermItem.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import TermFeatured from "../../../components/TermFeatured.vue";
import {useNavigationStore} from "../../../stores/NavigationStore.js";
import Paginator from "../../../Shared/Paginator.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useResourceIndex} from "../../../composables/resources/useResourceIndex.js";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

const featuredTerm = ref(null);

const letters = [
    'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق',
    'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', 'ء'
];

const {
    collection: terms,
    totalCount,
    filters,
    loading,
    currentPage,
    pageNumbers,
    goToPage,
    updateFilter,
} = useResourceIndex({
    routeBase: 'terms',
    defaultFilters: {sort: 'alphabetical'},
    toggleFilters: ['letter'],
    onResponse: (data) => {
        featuredTerm.value = data.featuredTerm;
    },
});

const selectedLetter = computed(() => filters.value.letter ?? null);
</script>

<template>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'terms.index' }" class="material-symbols-rounded">home</RouterLink>
                <div class="window-header-url">www.palweb.app/library/terms</div>
                <RouterLink v-if="UserStore.isAdmin" :to="{ name: 'word-logger.term' }" class="material-symbols-rounded">add
                </RouterLink>
                <RouterLink :to="{ name: 'terms.random' }" class="material-symbols-rounded">keyboard_double_arrow_right</RouterLink>
            </div>
            <div class="window-section-head"><h1>{{ $t('pages.terms.index.title') }}</h1></div>

            <TermFeatured v-if="featuredTerm" :model="featuredTerm"/>
            <div class="window-section-head"><h2>{{ $t('pages.common.index') }}</h2></div>

            <div class="letters-array">
                <button
                    v-for="letter in letters"
                    :key="letter"
                    :class="{ 'active': selectedLetter === letter }"
                    @click="updateFilter({ filter: 'letter', value: letter })"
                >{{ letter }}
                </button>
            </div>
            <SearchFilters activeModel="terms" :filters="filters" @updateFilter="updateFilter"/>

            <template v-if="loading">
                <AppTip>
                    <p>{{ $t('pages.common.loading', { model: $t('models.terms') }) }}</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        {{ $t('pages.common.displaying-results', { count: totalCount, model: $t('models.terms') }) }}
                        {{ $t(`pages.common.sort-messages.${filters.sort}`) }}
                    </p>
                    <p v-else-if="totalCount > 0">{{ $t('pages.common.displaying-all', { count: totalCount, model: $t('models.terms') }) }}</p>
                    <p v-else>
                        {{ $t('pages.common.displaying-none', { model: $t('models.terms') }) }}
                        Is a term missing from the Dictionary?
                        <a @click="NavigationStore.showSendFeedback = true" style="cursor: pointer">Click here to let us
                            know!</a>
                    </p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list">
                        <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
                    </div>
                    <Paginator
                        :pageNumbers="pageNumbers"
                        :currentPage="currentPage"
                        :goToPage="goToPage"
                    />
                </template>
            </template>
        </div>
    </div>
</template>

<style scoped lang="scss">
.letters-array {
    justify-items: center;
    padding: 3.2rem;
    background: var(--color-pastel-light);
    width: 100%;
    display: flex;
    flex-flow: row wrap;
    justify-content: center;
    gap: 1.6rem;
    direction: rtl;

    button {
        width: 4.8rem;
        height: 4.8rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--mono-font);
        font-size: 2.4rem;
        font-weight: 700;
        user-select: none;
        color: var(--color-accent-medium);
        background: white;

        &:hover {
            color: var(--color-dark-primary)
        }

        &.active {
            color: white;
            background: var(--color-dark-primary);
        }
    }
}
</style>
