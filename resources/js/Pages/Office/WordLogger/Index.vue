<script setup>
import {route} from "ziggy-js";
import Layout from "../../../Shared/Layout.vue";
import {onMounted, reactive, ref} from "vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import TermItem from "../../../components/TermItem.vue";

const tabs = [
    {key: 'relatives', label: 'Relatives', routeName: 'word-logger.relatives'},
    {key: 'sentences', label: 'Sentences', routeName: 'word-logger.sentences'},
    {key: 'inflections', label: 'Inflections', routeName: 'word-logger.inflections'},
];

const activeTab = ref('relatives');

const tabData = reactive({
    relatives: null,
    sentences: null,
    inflections: null,
});

const isLoading = reactive({
    relatives: false,
    sentences: false,
    inflections: false,
});

const loadTab = async (tabKey) => {
    if (tabData[tabKey] || isLoading[tabKey]) return;

    const tab = tabs.find(tab => tab.key === tabKey);
    if (!tab) return;

    isLoading[tabKey] = true;

    try {
        const response = await axios.get(route(tab.routeName));
        tabData[tabKey] = response.data;
    } finally {
        isLoading[tabKey] = false;
    }
};

const selectTab = async (tabKey) => {
    activeTab.value = tabKey;
    await loadTab(tabKey);
};

onMounted(async () => {
    await loadTab(activeTab.value);
});

defineOptions({
    layout: Layout
})
</script>
<template>
    <Head title="Word Logger"/>
    <div id="app-head">
        <h1>{{ $t('pages.word-logger.title') }}</h1>
    </div>
    <div id="app-body">
        <Link :href="route('word-logger.term')">{{ $t('forms.actions.create', {title: $t('actions.models.term')}) }}</Link>

        <div class="word-logger-tabs">
            <button v-for="tab in tabs"
                    :key="tab.key"
                    :class="{active: activeTab === tab.key}"
                    @click="selectTab(tab.key)">
                {{ tab.label }}
            </button>
        </div>

        <LoadingSpinner v-if="isLoading[activeTab]"/>

        <template v-else-if="activeTab === 'relatives' && tabData.relatives">
            <h1>Unlinked Reciprocal Relatives</h1>
            <div v-if="tabData.relatives.unlinkedReciprocalTerms.length" class="model-list index-list">
                <TermItem v-for="term in tabData.relatives.unlinkedReciprocalTerms"
                          :key="`unlinked-${term.id}`"
                          :model="term"/>
            </div>
            <div v-else class="empty-state">No terms have relative relationships missing reciprocal links.</div>

            <h1>Missing Relative Glosses</h1>
            <div v-if="tabData.relatives.missingGlossTerms.length" class="model-list index-list">
                <TermItem v-for="term in tabData.relatives.missingGlossTerms"
                          :key="`missing-gloss-${term.id}`"
                          :model="term"/>
            </div>
            <div v-else class="empty-state">No gloss-requiring relative relationships are missing glosses.</div>

            <h1>Missing Relative Types</h1>
            <div v-if="tabData.relatives.missingTypeTerms.length" class="model-list index-list">
                <TermItem v-for="term in tabData.relatives.missingTypeTerms"
                          :key="`missing-type-${term.id}`"
                          :model="term"/>
            </div>
            <div v-else class="empty-state">No relative relationships are missing types.</div>
        </template>

        <template v-else-if="activeTab === 'sentences' && tabData.sentences">
            <h1>From Sentences</h1>
            <div class="missing-terms">
                <div v-for="term in tabData.sentences.fromSentences" :key="term.id">
                    {{ term.sent_term }}
                    ({{ term.sent_translit }})

                    <Link :href="route('sentences.show', term.sentence_id)">{{ $t('actions.common.view', {model: 'Sentence'}) }}</Link>
                </div>
            </div>
            <div v-if="!tabData.sentences.fromSentences.length" class="empty-state">No sentence terms are waiting to be linked.</div>
        </template>

        <template v-else-if="activeTab === 'inflections' && tabData.inflections">
            <h1>Missing Inflections</h1>
            <div class="missing-terms">
                <div v-for="term in tabData.inflections.missingInflections" :key="term.id">
                    {{ term.inflection }}
                    {{ term.translit }}
                    ({{ term.form }})
                </div>
            </div>
            <div v-if="!tabData.inflections.missingInflections.length" class="empty-state">No missing inflection terms found.</div>
        </template>
    </div>
</template>

<style scoped lang="scss">
.word-logger-tabs {
    display: flex;
    gap: 0.8rem;
    margin: 1.6rem 0 2.4rem;

    button {
        border: 0;
        border-radius: 0.4rem;
        padding: 0.8rem 1.2rem;
        background: var(--color-pastel-light);
        font-family: var(--body-font);
        cursor: pointer;

        &.active {
            background: var(--color-accent);
            color: var(--color-light);
        }
    }
}

.missing-terms {
    display: grid;
    gap: 0.2rem;

    & > div {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;

        &:hover {
            background: var(--color-pastel-light);
        }
    }

    button {
        display: flex;
        align-items: center;
        font-family: var(--body-font);
        font-size: 1.6rem;
    }
}

.empty-state {
    padding: 1.2rem 0;
    color: var(--color-mid);
}
</style>
