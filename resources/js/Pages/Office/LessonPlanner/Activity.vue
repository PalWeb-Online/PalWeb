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
    errors,
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
    validationIssues,
    isValidRequest,
    publishIssues,
    isPublishable,
} = useActivityValidation({
    form,
    allowedBlockTypes,
});

const hasNavigationGuard = computed(() => isDirty.value && !isSaving.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <div v-else-if="activityNotFound" class="form-body" style="width: min(96rem, 100%); padding: 0">
            <p>Sorry, but the requested Activity does not exist.</p>
            <Link :href="route('lesson-planner.index')">
                Back to Lesson Planner
            </Link>
        </div>
        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="unit-meta">
                    <Link v-if="activity?.lesson?.id || initialLesson"
                          :href="route('lesson-planner.lesson', activity?.lesson?.id ?? initialLesson.id)">
                        <- to Lesson
                    </Link>
                    <Link v-if="activity?.id" :href="route('activities.activity', activity.id)">
                        View
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
                    <label>Title</label>
                    <div class="field-input">
                        <input type="text" v-model="form.title" :disabled="form.lesson_id"
                               placeholder="Title" required>
                    </div>
                </div>

                <div class="featured-title m">blocks</div>
                <DocumentBlocksManager :document-blocks="form.document.blocks"
                                       :block-types="allowedBlockTypes"
                />

                <AppTip>
                    <p>The Activity is currently {{ form.published ? 'Published' : 'a Draft' }}.</p>
                    <template v-if="!isValidRequest">
                        <p style="font-weight: 700">The Activity cannot be saved in the current state.</p>
                        <ul>
                            <li v-for="(issue, i) in validationIssues" :key="i">{{ issue }}</li>
                        </ul>
                    </template>
                    <template v-if="!isPublishable">
                        <p style="font-weight: 700">The Activity cannot be Published in the current state.</p>
                        <ul>
                            <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                        </ul>
                        <p v-if="form.published" style="font-weight: 700">Because the Activity is already Published, the
                            current state cannot be saved except by reverting it to Draft.</p>
                    </template>
                    <template v-if="Object.keys(errors).length">
                        <p style="font-weight: 700">Oops — the Activity could not be saved.</p>
                        <ul>
                            <li v-for="(error, key) in errors" :key="key">{{ key }}: {{ error }}</li>
                        </ul>
                    </template>
                </AppTip>
            </div>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button type="button"
                            @click="saveActivity({ publish: form.published })"
                            :disabled="isSaving || !hasNavigationGuard || !isValidRequest || (form.published && !isPublishable)">
                        Save
                    </button>
                    <button type="button" :disabled="!hasNavigationGuard" @click="reset()">Reset</button>
                    <button type="button"
                            @click="saveActivity({ publish: !form.published })"
                            :disabled="isSaving || !isValidRequest || (!form.published && !isPublishable) || (form.published && activity?.lesson?.published)"
                    >
                        {{ hasNavigationGuard ? 'Save & ' : '' }} {{
                            form?.published ? 'Revert to Draft' : 'Publish'
                        }}
                    </button>
                    <button type="button" @click="deleteActivity()">Delete Activity</button>
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
