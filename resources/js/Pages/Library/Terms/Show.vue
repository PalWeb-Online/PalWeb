<script setup>
import {onMounted, ref, watch} from 'vue';
import {useRouter} from 'vue-router';
import TermContainer from '../../../components/TermContainer.vue';
import {useTermViewer} from '../../../composables/terms/useTermViewer.js';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const router = useRouter();
const resolvedRandomTermSlug = ref(null);

const props = defineProps({
    termSlug: {
        type: String,
        default: null,
    },
    random: {
        type: Boolean,
        default: false,
    },
});

const {
    terms,
    primaryTerm,
    isLoadingTerms,
    loadTerms,
    loadRandomTerms,
    reloadTerms,
    reloadRandomTerms,
} = useTermViewer();

const openTerms = async (reload = false) => {
    if (props.random) {
        const randomTerms = await (reload ? reloadRandomTerms : loadRandomTerms)();
        const term = randomTerms?.[0];

        if (term?.slug) {
            resolvedRandomTermSlug.value = term.slug;
            router.replace({
                name: 'terms.show',
                params: { term: term.slug },
            });
        }

        return;
    }

    await (reload ? reloadTerms : loadTerms)(props.termSlug);
};

onMounted(() => {
    void openTerms();
});

watch(
    () => [props.termSlug, props.random],
    ([termSlug, random]) => {
        if (!random && termSlug === resolvedRandomTermSlug.value) {
            resolvedRandomTermSlug.value = null;
            return;
        }

        void openTerms(true);
    },
);

watch(
    primaryTerm,
    (term) => {
        document.title = term
            ? `Dictionary: ${term.term} (${term.translit})`
            : 'Dictionary';
    },
    { immediate: true },
);
</script>

<template>
    <LoadingWrapper :is-loading="isLoadingTerms || !terms">
        <div id="app-body">
            <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
        </div>
    </LoadingWrapper>
</template>
