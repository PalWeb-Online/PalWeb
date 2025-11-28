<script setup>
import {computed, ref, watch} from "vue";
import {debounce} from "lodash";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const emit = defineEmits([
    'updateFilter'
]);

const props = defineProps({
    activeModel: String,
    filters: Object,
});

const searchInput = ref(null);

const focusInput = () => {
    searchInput.value?.focus();
};

defineExpose({
    focusInput,
});

const filters = ref({
    search: props.filters.search || '',
    match: props.filters.match || 'term',
    sort: props.filters.sort || '',
    pinned: props.filters.pinned || false,
    category: props.filters.category || '',
    attribute: props.filters.attribute || '',
    form: props.filters.form || '',
    singular: props.filters.singular || '',
    plural: props.filters.plural || '',
});

let previousFilters = {...filters.value};

const debounceEmit = debounce((key, value) => {
    emit("updateFilter", {filter: key, value});
}, 250);

watch(
    filters,
    (newFilters) => {
        for (const key in newFilters) {
            if (newFilters[key] !== previousFilters[key]) {
                debounceEmit(key, newFilters[key]);
                previousFilters[key] = newFilters[key];
            }
        }
    },
    {deep: true}
);

watch(
    props.filters,
    (newPropFilters) => {
        for (const key in newPropFilters) {
            if (newPropFilters[key] !== filters.value[key]) {
                filters.value[key] = newPropFilters[key];
                previousFilters[key] = newPropFilters[key];
            }
        }
    }
);

// watch(
//     () => filters.value.pinned,
//     (newPinned) => {
//         if (newPinned) {
//             if (filters.value.sort !== 'pinned') {
//                 filters.value.sort = 'pinned';
//             }
//         } else {
//             const defaultSort = props.activeModel === 'terms' ? 'alphabetical' : 'latest';
//             if (filters.value.sort !== defaultSort) {
//                 filters.value.sort = defaultSort;
//             }
//         }
//     }
// );

const hasAttribute = computed(() => {
    const allowedCategories = ['', 'verb', 'noun', 'adjective', 'determiner'];
    return allowedCategories.includes(filters.value.category);
});

const hasForm = computed(() => {
    const allowedCategories = ['', 'verb', 'noun', 'adjective', 'numeral'];
    const allowedSingPatterns = ['', 'ap', 'pp', 'vn'];
    const disallowedAttributes = ['pseudo', 'defect'];
    return (
        allowedCategories.includes(filters.value.category) &&
        allowedSingPatterns.includes(filters.value.singular) &&
        !disallowedAttributes.includes(filters.value.attribute)
    );
});

const hasSingular = computed(() => {
    const allowedCategories = ['', 'noun', 'adjective', 'numeral'];
    return allowedCategories.includes(filters.value.category);
});

const hasPlural = computed(() => {
    const allowedCategories = ['', 'noun', 'adjective'];
    const disallowedAttributes = ['collective', 'demonym', 'defect'];
    return (
        allowedCategories.includes(filters.value.category) &&
        !disallowedAttributes.includes(filters.value.attribute)
    );
});

const isRegular = computed(() => {
    const allowedSingPatterns = ['CiCiC', 'CCīC', 'relative', 'ia'];
    return allowedSingPatterns.includes(filters.value.singular);
});

const isCCC = computed(() => {
    const allowedSingPatterns = ['CLC', 'CvCC', 'CvCCe', 'CvCvC'];
    return allowedSingPatterns.includes(filters.value.singular);
});
</script>

<template>
    <div class="search-filters-container">
        <select v-model="filters.match">
            <option value="root">Match Root</option>
            <option value="term">Match Term</option>
            <option value="gloss">Match Gloss</option>
        </select>

        <div class="search-bar">
            <div v-if="UserStore.isUser"
                 class="pin-button-wrapper" :class="{ pinned: filters.pinned }">
                <button class="material-symbols-rounded pin-button" @click="filters.pinned = !filters.pinned">
                    {{ filters.pinned ? 'keep' : 'keep_off' }}
                </button>
            </div>
            <input
                ref="searchInput"
                v-model="filters.search"
                :class="{'persisting': filters.search.length}"
                type="text"
                placeholder="دوّر"
            />
        </div>

        <select v-model="filters.sort"
                :class="((activeModel === 'terms' && filters.sort !== 'alphabetical') || (activeModel !== 'terms' && filters.sort !== 'latest')) ? 'persisting' : ''"
                v-if="['terms', 'decks'].includes(activeModel)">
            <option value="alphabetical" v-if="activeModel === 'terms'">Alphabetical by Root</option>
            <option value="latest">by Latest</option>
            <option value="popular" v-if="activeModel === 'decks'">by Popularity</option>
            <option value="pinned" v-if="filters.pinned">by Pinned</option>
        </select>

        <div class="search-filters" v-if="activeModel === 'terms'">
            <select v-model="filters.category" :class="filters.category ? 'persisting' : ''">
                <option value="">Category</option>
                <option value="verb">Verbs</option>
                <option value="noun">Nouns</option>
                <option value="adjective">Adjectives</option>
                <option value="numeral">Numerals</option>
                <option value="adverb">Adverbs</option>
                <option value="preposition">Prepositions</option>
                <option value="conjunction">Conjunctions</option>
                <option value="determiner">Determiners</option>
                <option value="particle">Particles</option>
                <option value="phrase">Phrases</option>
                <option value="affix">Affixes</option>
            </select>
            <select v-model="filters.attribute" :class="filters.attribute ? 'persisting' : ''">
                <option value="">Attribute</option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="masculine">
                    Masculine
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="feminine">
                    Feminine
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="plural">
                    Plural
                </option>
                <option v-if="filters.category === '' || filters.category === 'noun'" value="collective">
                    Collective
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'adjective'"
                    value="demonym">
                    Demonym
                </option>
                <option
                    v-if="(filters.category === '' || filters.category === 'adjective') && filters.form === ''"
                    value="defect">
                    Defect
                </option>
                <option v-if="(filters.category === '' || filters.category === 'verb') && filters.form === ''"
                        value="pseudo">
                    Pseudo
                </option>
                <option value="clitic">Clitic</option>
                <option value="idiom">Idiom</option>
            </select>
            <select v-model="filters.form" :class="filters.form ? 'persisting' : ''">
                <option value="">Form</option>
                <option value="1">Form 1</option>
                <template v-if="filters.category !== 'numeral' && filters.plural !== 'Cu22āC'">
                    <option value="2">Form 2</option>
                    <option value="3">Form 3</option>
                    <option value="4">Form 4</option>
                    <option value="5">Form 5</option>
                    <option value="6">Form 6</option>
                    <option value="7">Form 7</option>
                    <option value="8">Form 8</option>
                    <option value="9">Form 9</option>
                    <option value="X">Form X</option>
                    <option value="2Q">Form 2Q</option>
                    <option value="5Q">Form 5Q</option>
                </template>
            </select>
            <select v-model="filters.singular" :class="filters.singular ? 'persisting' : ''">
                <option value="">Singular</option>
                <optgroup v-if="filters.category === 'numeral'" label="Derived Terms">
                    <option value="ap">Act. Part.</option>
                </optgroup>
                <template v-if="filters.category !== 'numeral'">
                    <optgroup label="Derived Terms">
                        <option value="ap">AP</option>
                        <option value="pp">PP</option>
                        <option value="vn">VN</option>
                    </optgroup>
                    <optgroup label="Named Patterns">
                        <option value="relative">Relative Adj.</option>
                        <option value="ia">Intensive Adj.</option>
                        <option value="na">Nominalized Adj.</option>
                    </optgroup>
                    <optgroup label="Length 3">
                        <option value="CLC">CLC</option>
                        <option value="CVCC">CVCC</option>
                        <option value="CVCCe">CVCCe</option>
                        <option value="CVCVC">CVCVC</option>
                        <option value="CiCiC">CiCiC</option>
                    </optgroup>
                    <optgroup label="Length 4">
                        <option value="CCāC">CCāC</option>
                        <option value="CCīC">CCīC</option>
                        <option value="CCūC">CCūC</option>
                        <option value="CVCCVC">CVCCVC</option>
                        <option value="maCCVC">maCCVC</option>
                    </optgroup>
                    <optgroup label="Length 5">
                        <option value="CVCCLC">CVCCLC</option>
                        <option value="CaC:āC">CaC:āC</option>
                        <option value="CaC:īC">CaC:īC</option>
                        <option value="CaC:ūC">CaC:ūC</option>
                    </optgroup>
                </template>
            </select>
            <select v-model="filters.plural" :class="filters.plural ? 'persisting' : ''">
                <option value="">Plural</option>
                <optgroup v-if="filters.singular === '' || isRegular" label="Sound">
                    <option value="-īn">-īn</option>
                    <option value="-āt">-āt</option>
                </optgroup>
                <optgroup label="Length 3">
                    <template v-if="filters.singular === '' || isCCC">
                        <option value="CCūC">CCūC</option>
                        <option value="ʔaCCāC">ʔaCCāC</option>
                        <option value="CVCaC">CVCaC</option>
                    </template>
                </optgroup>
                <optgroup v-if="!isRegular" label="Length 3 / 4">
                    <option value="CCāC">CCāC</option>
                    <option value="CVCCān">CVCCān</option>
                </optgroup>
                <optgroup
                    v-if="filters.singular === '' || (filters.singular === 'CCāC' || filters.singular === 'CCīC')"
                    label="Length 4">
                    <option v-if="!isRegular" value="CaCāCiC">CaCāCiC</option>
                    <option value="CuC:āC" v-if="filters.singular === '' || filters.singular === 'ap'">CuC:āC</option>
                    <option value="CuCuC">CuCuC</option>
                    <template v-if="filters.singular !== 'CCīC'">
                        <option value="ʔaCCiCe">ʔaCCiCe</option>
                        <option value="CCīC">CCīC</option>
                    </template>
                    <option value="CuCaCa" v-if="filters.singular !== 'CCāC'">CuCaCa</option>
                </optgroup>
                <optgroup
                    v-if="filters.singular === '' || filters.singular === 'CVCCLC' || filters.singular === 'relative'"
                    label="Length 5">
                    <option v-if="filters.singular !== 'relative'" value="CaCāCīC">CaCāCīC</option>
                    <option value="CaCāCCe">CaCāCCe</option>
                </optgroup>
            </select>
        </div>
    </div>
</template>
