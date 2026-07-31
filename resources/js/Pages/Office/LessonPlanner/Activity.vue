<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {computed, onMounted, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import {useActivityValidation} from "../../../composables/activities/useActivityValidation.js";
import {useActivityEditor} from "../../../composables/activities/useActivityEditor.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import DocumentBlocksManager from "../../../components/Blocks/Editors/DocumentBlocksManager.vue";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    activityId: {
        type: Number,
        default: null,
    },
    initialLesson: {
        type: Object,
        default: null,
    },
});

const {
    form,
    errors: backendErrors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    activity,
    activityNotFound,
    allowedBlockTypes,
    loadForm,
    reloadForm,
    saveActivity,
    deleteActivity,
} = useActivityEditor({
    activityId: computed(() => props.activityId),
    initialLesson: computed(() => props.initialLesson),
});

onMounted(async () => {
    await loadForm();
});

watch(() => props.activityId, async () => {
    await reloadForm();
});

const {
    isValidRequest,
    validationErrors,
    publishIssues,
    isPublishable,
} = useActivityValidation({
    form,
    backendErrors,
    allowedBlockTypes,
});

const hasNavigationGuard = computed(() => isDirty.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>{{ $t('pages.lesson-planner.title') }}</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="activityNotFound">
            <AppTip>
                <p>{{ $t('pages.common.not-found', {model: $t('actions.models.activity')}) }}</p>
            </AppTip>
            <Link class="portal-button" :href="route('lesson-planner.index')">
                Back to Lesson Planner
            </Link>
        </template>

        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="unit-meta">
                    <Link v-if="activity?.lesson?.id || initialLesson"
                          :href="route('lesson-planner.lesson', activity?.lesson?.id ?? initialLesson.id)">
                        <- to Lesson
                    </Link>
                </div>
                <div class="featured-title l">
                    {{ form.title }}
                </div>
                <AppTip v-if="activity?.lesson?.published && form.published">
                    <p><b>WARNING:</b> You are editing an Activity for a published Lesson. If you intend to revert it to
                        a
                        draft, <b>you must revert the Lesson to a draft first</b>. Otherwise, if you make any changes to
                        the
                        Activity now that would render it unpublishable, you will not be able to save those changes.</p>
                </AppTip>
                <div class="field-item">
                    <label>{{ $t('forms.fields.title') }}</label>
                    <div class="field-input">
                        <input type="text" v-model="form.title" :disabled="form.lesson_id"
                               placeholder="Title" required>
                    </div>
                </div>

                <div class="featured-title m">{{ $t('document.fields.blocks') }}</div>
                <DocumentBlocksManager :document-blocks="form.document.blocks"
                                       :block-types="allowedBlockTypes"
                />

                <AppTip>
                    <p>{{
                            $t('forms.messages.current-status', {
                                model: $t('actions.models.activity'),
                                status: form.published ? $t('forms.status.published') : $t('forms.status.draft')
                            })
                        }}</p>
                    <template v-if="Object.keys(validationErrors).length">
                        <p style="font-weight: 700">{{ $t('forms.messages.has-validation-errors', {model: $t('actions.models.activity')}) }}</p>
                        <ul>
                            <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                        </ul>
                    </template>
                    <template v-if="!isPublishable">
                        <p style="font-weight: 700">{{ $t('forms.messages.has-publish-issues', {model: $t('actions.models.activity')}) }}</p>
                        <ul>
                            <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                        </ul>
                        <p v-if="form.published" style="font-weight: 700">Because the Activity is already Published, the
                            current state cannot be saved except by reverting it to Draft.</p>
                    </template>
                </AppTip>
            </div>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button
                        type="button"
                        @click="saveActivity({ publish: form.published })"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest || (form.published && !isPublishable)"
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
                    <button type="button"
                            @click="saveActivity({ publish: !form.published })"
                            :disabled="isSaving || !isValidRequest || (!form.published && !isPublishable) || (form.published && activity?.lesson?.published)"
                    >
                        {{
                            hasNavigationGuard ? $t('forms.actions.save') + ' & ' : ''
                        }} {{
                            form?.published ? $t('forms.actions.set-status.draft') : $t('forms.actions.set-status.published')
                        }}
                    </button>
                    <button type="button" @click="deleteActivity()">
                        {{ $t('actions.common.delete', {model: $t('actions.models.activity')}) }}
                    </button>
                    <Link v-if="activity?.id" :href="route('activities.activity', activity.id)">
                        {{ $t('actions.common.view', {model: $t('actions.models.activity')}) }}
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
</template>
