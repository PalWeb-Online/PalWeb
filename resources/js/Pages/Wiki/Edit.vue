<script setup>
import Layout from "../../Shared/Layout.vue";
import {computed, onMounted, provide, watch} from "vue";
import {route} from "ziggy-js";
import {useNavGuard} from "../../composables/NavGuard.js";
import NavGuard from "../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../components/Modals/ModalWrapper.vue";
import AppTip from "../../components/AppTip.vue";
import DocumentBlocksManager from "../../components/Blocks/Editors/DocumentBlocksManager.vue";
import LoadingSpinner from "../../Shared/LoadingSpinner.vue";
import SearchSelect from "../../components/SearchSelect.vue";
import {usePageSearch} from "../../composables/usePageSearch.js";
import {usePageEditor} from "../../composables/pages/usePageEditor.js";
import {usePageValidation} from "../../composables/pages/usePageValidation.js";

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
    errors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    page,
    pageNotFound,
    descendantIds,
    sentenceModels,
    allowedBlockTypes,
    selectedParent,
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

onMounted(async () => {
    await loadForm();
});

watch(() => props.pageId, async () => {
    await reloadForm();
});

const {
    validationIssues,
    isValidRequest,
    publishIssues,
    isPublishable,
} = usePageValidation({
    form,
    page,
    descendantIds,
    allowedBlockTypes,
});

const hasNavigationGuard = computed(() => isDirty.value && !isSaving.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>

<template>
    <Head :title="page?.id ? `Edit Wiki: ${page?.title || 'Page'}` : 'Create Wiki Page'"/>

    <div id="app-head">
        <h1>wiki</h1>
    </div>

    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <div v-if="pageNotFound" class="form-body" style="width: min(96rem, 100%); padding: 0">
            <p>Sorry, but the requested Page does not exist.</p>
            <Link :href="route('wiki.index')">
                Back to Wiki
            </Link>
        </div>
        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="featured-title l">
                    <span v-if="page?.id">Edit: {{ form.title || 'Page' }}</span>
                    <span v-else>Create: {{ form.title || 'Page' }}</span>
                </div>

                <div class="field-item">
                    <label>Slug</label>
                    <input type="text" v-model="form.slug" placeholder="page-slug-goes-here" required>
                </div>

                <div class="field-item">
                    <label>Title</label>
                    <input type="text" v-model="form.title" placeholder="Page Title" required>
                </div>

                <div class="field-item">
                    <label>Summary</label>
                    <textarea v-model="form.summary" placeholder="Page Summary"/>
                </div>

                <div class="field-item">
                    <label>Locale</label>
                    <select v-model="form.locale">
                        <option value="en">English</option>
                        <option value="ar">عربيّ</option>
                        <option value="es">Español</option>
                    </select>
                </div>

                <div class="field-item">
                    <label>Sort Order</label>
                    <input type="number" v-model="form.sort_order" min="0">
                </div>

                <SearchSelect
                    v-model="form.parent_id"
                    label="Parent Page"
                    :initial-title="selectedParent?.title"
                    :search="searchPages"
                    :error="errors?.parent_id"
                    @select="setSelectedParent"
                    @clear="setSelectedParent()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.title }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                /{{ option.slug }}
                            </div>
                        </div>
                    </template>
                </SearchSelect>

                <DocumentBlocksManager
                    :document-blocks="form.document.blocks"
                    :block-types="allowedBlockTypes"
                />
            </div>

            <AppTip>
                <p>The Wiki page is currently {{ form.status }}.</p>

                <template v-if="!isValidRequest">
                    <p style="font-weight: 700">The Page cannot be saved in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in validationIssues" :key="i">{{ issue }}</li>
                    </ul>
                </template>
                <template v-if="!isPublishable">
                    <p style="font-weight: 700">The Page cannot be published in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                    </ul>
                </template>
                <template v-if="Object.keys(errors).length">
                    <p style="font-weight: 700">Oops — the Page could not be saved.</p>
                    <ul>
                        <li v-for="(error, key) in errors" :key="key">{{ key }}: {{ error }}</li>
                    </ul>
                </template>
            </AppTip>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button
                        type="button"
                        @click="savePage({ publish: form.status === 'published' })"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest || (form.status === 'published' && !isPublishable)"
                    >
                        Save
                    </button>

                    <button
                        type="button"
                        :disabled="!hasNavigationGuard"
                        @click="reset()"
                    >
                        Reset
                    </button>

                    <button
                        type="button"
                        @click="savePage({ publish: form.status !== 'published' })"
                        :disabled="isSaving || !isValidRequest || (form.status !== 'published' && !isPublishable)"
                    >
                        {{
                            hasNavigationGuard ? 'Save & ' : ''
                        }}{{ form.status === 'published' ? 'Revert to Draft' : 'Publish' }}
                    </button>

                    <button
                        v-if="pageId"
                        type="button"
                        @click="deletePage"
                    >
                        Delete Page
                    </button>

                    <Link :href="route('wiki.index')">
                        Back to Wiki
                    </Link>
                </div>
            </div>
        </template>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
