<script setup>
import Layout from "../../Shared/Layout.vue";
import {computed, onMounted, provide, ref, watch} from "vue";
import {route} from "ziggy-js";
import {useNavGuard} from "../../composables/NavGuard.js";
import NavGuard from "../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../components/Modals/ModalWrapper.vue";
import AppTip from "../../components/AppTip.vue";
import DocumentBlocksManager from "../../components/Blocks/Editors/DocumentBlocksManager.vue";
import LoadingSpinner from "../../Shared/LoadingSpinner.vue";
import SearchSelect from "../../components/SearchSelect.vue";
import {usePageSearch} from "../../composables/pages/usePageSearch.js";
import {usePageEditor} from "../../composables/pages/usePageEditor.js";
import {usePageValidation} from "../../composables/pages/usePageValidation.js";
import PagePositionModal from "./UI/PagePositionModal.vue";

defineOptions({
    layout: Layout,
});

const props = defineProps({
    pageId: {
        type: Number,
        required: false,
    },
});

const {
    form,
    errors: backendErrors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    page,
    pageNotFound,
    pageTree,
    isLoadingTree,
    descendantIds,
    sentenceModels,
    allowedBlockTypes,
    selectedParent,
    fetchWikiTree,
    loadForm,
    reloadForm,
    savePage,
    deletePage,
    setSelectedParent,
} = usePageEditor({
    pageId: computed(() => props.pageId),
});

const {
    searchPages,
} = usePageSearch({
    currentPageId: computed(() => props.pageId),
});

provide('documentSentenceModels', sentenceModels);

const showPagePositionModal = ref(false);

const findPageInTree = (pages, pageId) => {
    if (!pageId) return null;

    for (const page of pages ?? []) {
        if (Number(page.id) === Number(pageId)) return page;

        const childMatch = findPageInTree(page.children ?? [], pageId);
        if (childMatch) return childMatch;
    }

    return null;
};

const pagePositionParentTitle = computed(() => {
    if (!form.parent_id) return 'Root';

    return selectedParent.value?.title
        ?? findPageInTree(pageTree.value, form.parent_id)?.title
        ?? 'Selected Parent';
});

const setPagePosition = (position) => {
    form.position = position;
};

onMounted(async () => {
    await Promise.all([
        fetchWikiTree(),
        loadForm(),
    ]);
});

watch(() => props.pageId, async () => {
    await reloadForm();
});

const {
    validationErrors,
    isValidRequest,
    publishIssues,
    isPublishable,
} = usePageValidation({
    form,
    page,
    backendErrors,
    descendantIds,
    allowedBlockTypes,
});

const hasNavigationGuard = computed(() => isDirty.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>

<template>
    <Head :title="page?.id ? `Edit Wiki: ${page?.title || 'Page'}` : 'Create Wiki Page'"/>
    <div id="app-head">
        <h1>{{ $t('pages.wiki.title') }}</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="pageNotFound">
            <AppTip>
                <p>{{ $t('pages.common.not-found', {model: $t('actions.models.page')}) }}</p>
            </AppTip>
            <Link class="portal-button" :href="route('wiki.index')">
                Back to Wiki
            </Link>
        </template>

        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="featured-title l">
                    <template v-if="page?.id">{{ $t('forms.actions.edit', {title: form.title || $t('actions.models.page')}) }}</template>
                    <template v-else>{{ $t('forms.actions.create', {title: form.title || $t('actions.models.page')}) }}</template>
                </div>

                <div class="field-item">
                    <label>{{ $t('forms.fields.slug') }}</label>
                    <input type="text" v-model="form.slug" placeholder="page-slug-goes-here" required>
                </div>

                <div class="field-item">
                    <label>{{ $t('forms.fields.title') }}</label>
                    <input type="text" v-model="form.title" placeholder="Page Title" required>
                </div>

                <div class="field-item">
                    <label>{{ $t('forms.fields.summary') }}</label>
                    <textarea v-model="form.summary" placeholder="Page Summary"/>
                </div>

                <div class="field-item">
                    <label>{{ $t('forms.fields.locale') }}</label>
                    <select v-model="form.locale">
                        <option value="en">English</option>
                        <option value="ar">عربيّ</option>
                        <option value="es">Español</option>
                    </select>
                </div>

                <SearchSelect
                    v-model="form.parent_id"
                    :label="$t('forms.fields.parent')"
                    :initial-title="selectedParent?.title"
                    :search="searchPages"
                    :error="validationErrors[`parent_id`]"
                    @select="setSelectedParent"
                    @clear="setSelectedParent()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.title }}</strong>
                            <div style="font-size: 0.85em; color: var(--color-medium-secondary)">
                                /{{ option.slug }}
                            </div>
                        </div>
                    </template>
                </SearchSelect>

                <div class="field-item">
                    <label>{{ $t('forms.fields.position') }}</label>
                    <div class="page-position-field">
                        <span>{{ $t('actions.models.page') }}</span>
                        <input type="number" v-model="form.position" min="1">
                        <span>@ {{ pagePositionParentTitle }}</span>
                        <button
                            type="button"
                            @click="showPagePositionModal = true"
                            :disabled="isLoadingTree"
                        >
                            {{ $t('actions.common.select', {thing: $t('forms.fields.position')}) }}
                        </button>
                    </div>
                </div>

                <DocumentBlocksManager
                    :document-blocks="form.document.blocks"
                    :block-types="allowedBlockTypes"
                />

                <AppTip>
                    <p>{{
                            $t('forms.messages.current-status', {
                                model: $t('actions.models.page'),
                                status: $t(`forms.status.${form.status}`)
                            })
                        }}</p>
                    <template v-if="Object.keys(validationErrors).length">
                        <p style="font-weight: 700">{{ $t('forms.messages.has-validation-errors', {model: $t('actions.models.page')}) }}</p>
                        <ul>
                            <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                        </ul>
                    </template>
                    <template v-if="!isPublishable">
                        <p style="font-weight: 700">{{ $t('forms.messages.has-publish-issues', {model: $t('actions.models.page')}) }}</p>
                        <ul>
                            <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                        </ul>
                    </template>
                </AppTip>
            </div>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button
                        type="button"
                        @click="savePage({ publish: form.status === 'published' })"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest || (form.status === 'published' && !isPublishable)"
                    >
                        {{ $t('forms.actions.save') }}
                    </button>
                    <button
                        type="button"
                        @click="reset()"
                        :disabled="!hasNavigationGuard"
                    >
                        {{ $t('forms.actions.reset') }}
                    </button>
                    <button
                        type="button"
                        @click="savePage({ publish: form.status !== 'published' })"
                        :disabled="isSaving || !isValidRequest || (form.status !== 'published' && !isPublishable)"
                    >
                        {{
                            hasNavigationGuard ? $t('forms.actions.save') + ' & ' : ''
                        }} {{
                            form.status === 'published' ? $t('forms.actions.set-status.draft') : $t('forms.actions.set-status.published')
                        }}
                    </button>

                    <button v-if="pageId" type="button" @click="deletePage()">
                        {{ $t('actions.common.delete', {model: $t('actions.models.page')}) }}
                    </button>

                    <Link v-if="page" :href="route('wiki.show', page.slug)">
                        {{ $t('actions.common.view', {model: $t('actions.models.page')}) }}
                    </Link>
                    <Link v-else :href="route('wiki.index')">
                        Back to Wiki
                    </Link>
                </div>
            </div>
        </template>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="$t('modals.nav-guard.messages.unsaved-changes')"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>

    <ModalWrapper v-model="showPagePositionModal">
        <PagePositionModal
            v-model="showPagePositionModal"
            :page-tree="pageTree"
            :parent-id="form.parent_id"
            :current-page-id="page?.id"
            :current-title="form.title"
            :position="form.position"
            :is-loading="isLoadingTree"
            @select="setPagePosition"
        />
    </ModalWrapper>
</template>

<style scoped lang="scss">
.page-position-field {
    display: flex;
    gap: 0.8rem;
    align-items: center;
    font-family: var(--mono-font);
    font-size: 1.4rem;
    color: var(--color-medium-primary);
}
</style>
