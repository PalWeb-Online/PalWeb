<script setup>
import {onMounted, ref, watch} from 'vue';
import {useRouter} from 'vue-router';
import TermContainer from '../../../components/TermContainer.vue';
import LoadingSpinner from '../../../Shared/LoadingSpinner.vue';
import {useTermViewer} from '../../../composables/terms/useTermViewer.js';
import AppTip from '../../../components/AppTip.vue';

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
    termsNotFound,
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
    <LoadingSpinner v-if="isLoadingTerms"/>
    <AppTip v-else-if="termsNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.term')}) }}</p>
    </AppTip>
    <div v-else-if="terms?.length > 0" id="app-body">
        <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
    </div>
</template>
