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
    () => props.filters,
    (newPropFilters) => {
        for (const key in newPropFilters) {
            if (newPropFilters[key] !== filters.value[key]) {
                filters.value[key] = newPropFilters[key];
                previousFilters[key] = newPropFilters[key];
            }
        }
    },
    {deep: true, immediate: true}
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
    const allowedSingPatterns = ['CiCiC', 'ia'];
    return allowedSingPatterns.includes(filters.value.singular);
});

const isCCC = computed(() => {
    const allowedSingPatterns = ['CLC', 'CVCC', 'CVCCe', 'CVCVC'];
    return allowedSingPatterns.includes(filters.value.singular);
});
</script>

<template>
    <div class="search-filters-container">
        <select v-model="filters.match">
            <option value="root">{{ $t('search.filters.match.root') }}</option>
            <option value="term">{{ $t('search.filters.match.term') }}</option>
            <option value="gloss">{{ $t('search.filters.match.gloss') }}</option>
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
                :placeholder="$t('search.filters.placeholder')"
            />
        </div>

        <select v-model="filters.sort"
                :class="((activeModel === 'terms' && filters.sort !== 'alphabetical') || (activeModel !== 'terms' && filters.sort !== 'latest')) ? 'persisting' : ''"
                v-if="['terms', 'decks'].includes(activeModel)">
            <option value="alphabetical" v-if="activeModel === 'terms'">{{ $t('search.filters.sort.alphabetical-root') }}</option>
            <option value="frequency" v-if="activeModel === 'terms'">{{ $t('search.filters.sort.frequency') }}</option>
            <option value="latest">{{ $t('search.filters.sort.latest') }}</option>
            <option value="popular" v-if="activeModel === 'decks'">{{ $t('search.filters.sort.popularity') }}</option>
            <option value="pinned" v-if="filters.pinned">{{ $t('search.filters.sort.pinned') }}</option>
        </select>

        <div class="search-filters" v-if="activeModel === 'terms'">
            <select v-model="filters.category" :class="filters.category ? 'persisting' : ''">
                <option value="">{{ $t('term.fields.category') }}</option>
                <option value="verb">{{ $t('term.filters.categories.verbs') }}</option>
                <option value="noun">{{ $t('term.filters.categories.nouns') }}</option>
                <option value="adjective">{{ $t('term.filters.categories.adjectives') }}</option>
                <option value="numeral">{{ $t('term.filters.categories.numerals') }}</option>
                <option value="adverb">{{ $t('term.filters.categories.adverbs') }}</option>
                <option value="preposition">{{ $t('term.filters.categories.prepositions') }}</option>
                <option value="conjunction">{{ $t('term.filters.categories.conjunctions') }}</option>
                <option value="determiner">{{ $t('term.filters.categories.determiners') }}</option>
                <option value="particle">{{ $t('term.filters.categories.particles') }}</option>
                <option value="phrase">{{ $t('term.filters.categories.phrases') }}</option>
                <option value="affix">{{ $t('term.filters.categories.affixes') }}</option>
            </select>
            <select v-model="filters.attribute" :class="filters.attribute ? 'persisting' : ''">
                <option value="">{{ $t('term.fields.attribute') }}</option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="masculine">
                    {{ $t('term.filters.attributes.masculine') }}
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="feminine">
                    {{ $t('term.filters.attributes.feminine') }}
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="plural">
                    {{ $t('term.filters.attributes.plural') }}
                </option>
                <option v-if="filters.category === '' || filters.category === 'noun'" value="collective">
                    {{ $t('term.filters.attributes.collective') }}
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'adjective'"
                    value="demonym">
                    {{ $t('term.filters.attributes.demonym') }}
                </option>
                <option
                    v-if="(filters.category === '' || filters.category === 'adjective') && filters.form === ''"
                    value="defect">
                    {{ $t('term.filters.attributes.defect') }}
                </option>
                <option v-if="(filters.category === '' || filters.category === 'verb') && filters.form === ''"
                        value="pseudo">
                    {{ $t('term.filters.attributes.pseudo') }}
                </option>
                <option value="clitic">{{ $t('term.filters.attributes.clitic') }}</option>
                <option value="idiom">{{ $t('term.filters.attributes.idiom') }}</option>
            </select>
            <select v-model="filters.form" :class="filters.form ? 'persisting' : ''">
                <option value="">{{ $t('term.filters.form.label') }}</option>
                <option value="1">{{ $t('term.filters.form.option', { form: 1 }) }}</option>
                <template v-if="filters.category !== 'numeral' && filters.plural !== 'Cu22āC'">
                    <option value="2">{{ $t('term.filters.form.option', { form: 2 }) }}</option>
                    <option value="3">{{ $t('term.filters.form.option', { form: 3 }) }}</option>
                    <option value="4">{{ $t('term.filters.form.option', { form: 4 }) }}</option>
                    <option value="5">{{ $t('term.filters.form.option', { form: 5 }) }}</option>
                    <option value="6">{{ $t('term.filters.form.option', { form: 6 }) }}</option>
                    <option value="7">{{ $t('term.filters.form.option', { form: 7 }) }}</option>
                    <option value="8">{{ $t('term.filters.form.option', { form: 8 }) }}</option>
                    <option value="9">{{ $t('term.filters.form.option', { form: 9 }) }}</option>
                    <option value="X">{{ $t('term.filters.form.option', { form: 'X' }) }}</option>
                    <option value="2Q">{{ $t('term.filters.form.option', { form: '2Q' }) }}</option>
                    <option value="5Q">{{ $t('term.filters.form.option', { form: '5Q' }) }}</option>
                </template>
            </select>
            <select v-model="filters.singular" :class="filters.singular ? 'persisting' : ''">
                <option value="">{{ $t('term.filters.singular') }}</option>
                <optgroup v-if="filters.category === 'numeral'" :label="$t('term.filters.pattern-groups.derived-terms')">
                    <option value="ap">{{ $t('term.filters.patterns.active-participle-abbr') }}</option>
                </optgroup>
                <template v-if="filters.category !== 'numeral'">
                    <optgroup :label="$t('term.filters.pattern-groups.derived-terms')">
                        <option value="ap">AP</option>
                        <option value="pp">PP</option>
                        <option value="vn">VN</option>
                    </optgroup>
                    <optgroup :label="$t('term.filters.pattern-groups.named-patterns')">
                        <option value="relative">{{ $t('term.filters.patterns.relative-adjective-abbr') }}</option>
                        <option value="ia">{{ $t('term.filters.patterns.intensive-adjective-abbr') }}</option>
                        <option value="na">{{ $t('term.filters.patterns.nominalized-adjective-abbr') }}</option>
                    </optgroup>
                    <optgroup :label="$t('term.filters.pattern-groups.length', { length: 3 })">
                        <option value="CLC">CLC</option>
                        <option value="CVCC">CVCC</option>
                        <option value="CVCCe">CVCCe</option>
                        <option value="CVCVC">CVCVC</option>
                        <option value="CiCiC">CiCiC</option>
                    </optgroup>
                    <optgroup :label="$t('term.filters.pattern-groups.length', { length: 4 })">
                        <option value="CCāC">CCāC</option>
                        <option value="CCīC">CCīC</option>
                        <option value="CCūC">CCūC</option>
                        <option value="CVCCVC">CVCCVC</option>
                        <option value="maCCVC">maCCVC</option>
                    </optgroup>
                    <optgroup :label="$t('term.filters.pattern-groups.length', { length: 5 })">
                        <option value="CVCCLC">CVCCLC</option>
                        <option value="CaC:āC">CaC:āC</option>
                        <option value="CaC:īC">CaC:īC</option>
                        <option value="CaC:ūC">CaC:ūC</option>
                    </optgroup>
                </template>
            </select>
            <select v-model="filters.plural" :class="filters.plural ? 'persisting' : ''">
                <option value="">{{ $t('term.filters.plural') }}</option>
                <optgroup v-if="filters.singular === '' || isRegular" :label="$t('term.filters.pattern-groups.sound')">
                    <option value="-īn">-īn</option>
                    <option value="-āt">-āt</option>
                </optgroup>
                <optgroup :label="$t('term.filters.pattern-groups.length', { length: 3 })">
                    <template v-if="filters.singular === '' || isCCC">
                        <option value="CCūC">CCūC</option>
                        <option value="ʔaCCāC">ʔaCCāC</option>
                        <option value="CVCaC">CVCaC</option>
                    </template>
                </optgroup>
                <optgroup v-if="!isRegular" :label="$t('term.filters.pattern-groups.length-range', { start: 3, end: 4 })">
                    <option value="CCāC">CCāC</option>
                    <option value="CVCCān">CVCCān</option>
                </optgroup>
                <optgroup
                    v-if="filters.singular === '' || (filters.singular === 'CCāC' || filters.singular === 'CCīC')"
                    :label="$t('term.filters.pattern-groups.length', { length: 4 })">
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
                    :label="$t('term.filters.pattern-groups.length', { length: 5 })">
                    <option v-if="filters.singular !== 'relative'" value="CaCāCīC">CaCāCīC</option>
                    <option value="CaCāCCe">CaCāCCe</option>
                </optgroup>
            </select>
        </div>
    </div>
</template>
