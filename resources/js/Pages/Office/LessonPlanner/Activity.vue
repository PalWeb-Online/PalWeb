<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import DocumentBlocksManager from "./UI/DocumentBlocksManager.vue";

defineOptions({
    layout: Layout,
})

const NotificationStore = useNotificationStore();

const props = defineProps({
    lesson: Object,
    activity: Object,
});

const activity = useForm({
    lesson_id: props.lesson.id,
    title: 'Activity ' + props.lesson.slug + ': ' + props.lesson.title,
    document: props.activity?.document || {
        schemaVersion: 1,
        blocks: []
    },
    published: props.activity?.published || false,
});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return activity.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const saveActivity = async ({publish}) => {
    isSaving.value = true;
    activity.published = !!publish;

    const method = props.activity?.id
        ? activity.patch.bind(activity)
        : activity.post.bind(activity);

    const url = props.activity?.id
        ? route('activities.update', props.activity.id)
        : route('activities.store');

    method(url, {
        onSuccess: () => {
            activity.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Activity could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    }, {preserveScroll: true});
};

const deleteActivity = () => {
    if (!confirm('Are you sure you want to delete this Activity?')) return;

    router.delete(route('activities.destroy', props.activity.id));
};

const isNonEmptyString = (v) => typeof v === 'string' && v.trim().length > 0;

const publishIssues = computed(() => {
    const issues = [];

    const blocks = activity.document?.blocks ?? [];
    const exerciseBlocks = blocks.filter(b => b?.type === 'exercises');
    const hasExerciseBlock = exerciseBlocks.length > 0;

    if (!hasExerciseBlock) {
        issues.push('At least one Exercises Block is required.');
    }

    blocks.forEach((block, blockIndex) => {
        const where = `Block ${blockIndex + 1} (${block.type})`;

        if (block.type === 'text') {
            if (!isNonEmptyString(block.content)) {
                issues.push(`${where}: Text Block content cannot be empty.`);
            }
        }

        if (block.type === 'audio') {
            if (!isNonEmptyString(block.media)) {
                issues.push(`${where}: Audio Block media cannot be empty.`);
            }
        }

        if (block.type === 'table') {
            const cols = block.columns ?? [];
            const rows = block.rows ?? [];

            if (cols.length < 1) issues.push(`${where}: Table Block must have at least 1 Column.`);
            if (rows.length < 1) issues.push(`${where}: Table Block must have at least 1 Row.`);

            cols.forEach((col, colIndex) => {
                if (!isNonEmptyString(col?.label)) {
                    issues.push(`${where}: Column ${colIndex + 1} label cannot be empty.`);
                }
            });

            rows.forEach((row, rowIndex) => {
                cols.forEach((col, colIndex) => {
                    const cell = row?.cells?.[col.id];
                    if (!isNonEmptyString(cell)) {
                        issues.push(`${where}: Row ${rowIndex + 1}, Column ${colIndex + 1} cell cannot be empty.`);
                    }
                });
            });
        }

        if (block.type === 'exercises') {
            const items = block.items ?? [];

            (block.examples ?? []).forEach((ex, exIndex) => {
                if (!isNonEmptyString(ex?.prompt)) issues.push(`${where}: Example ${exIndex + 1} prompt is empty.`);
                if (!isNonEmptyString(ex?.answer)) issues.push(`${where}: Example ${exIndex + 1} answer is empty.`);
            });

            if (items.length === 0) {
                issues.push(`${where}: Exercises Block must have at least one Exercise.`);
            }

            items.forEach((ex, exIndex) => {
                const exWhere = `${where}: Exercise ${exIndex + 1} (${ex.type})`;

                if (!isNonEmptyString(ex?.prompt)) {
                    issues.push(`${exWhere}: prompt cannot be empty.`);
                }

                (ex.images ?? []).forEach((img, imgIndex) => {
                    if (!isNonEmptyString(img)) {
                        issues.push(`${exWhere}: image ${imgIndex + 1} URL is empty.`);
                    }
                });

                if (ex.type === 'input') {
                    const answers = Array.isArray(ex.answers) ? ex.answers : [];
                    const hasAnyAnswer = answers.some(a => isNonEmptyString(a));
                    if (!hasAnyAnswer) {
                        issues.push(`${exWhere}: must have at least one non-empty accepted answer.`);
                    }
                }

                if (ex.type === 'select') {
                    const options = Array.isArray(ex.options) ? ex.options : [];
                    const anyEmpty = options.some(o => !isNonEmptyString(o.text));
                    if (anyEmpty) {
                        issues.push(`${exWhere}: option text cannot be empty.`);
                    }

                    const optionIds = options.map(o => o.id);
                    if (!ex.answerId || !optionIds.includes(ex.answerId)) {
                        issues.push(`${exWhere}: a correct answer must be selected.`);
                    }
                }

                if (ex.type === 'match') {
                    const pairs = Array.isArray(ex.pairs) ? ex.pairs : [];
                    if (pairs.length < 2) {
                        issues.push(`${exWhere}: must have at least 2 pairs.`);
                    }
                    pairs.forEach((p, pairIndex) => {
                        if (!isNonEmptyString(p?.start)) {
                            issues.push(`${exWhere}: pair ${pairIndex + 1} start side cannot be empty.`);
                        }
                        if (!isNonEmptyString(p?.end)) {
                            issues.push(`${exWhere}: pair ${pairIndex + 1} end side cannot be empty.`);
                        }
                    });
                }
            });
        }
    });

    return issues;
});

const isPublishable = computed(() => publishIssues.value.length === 0);
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%); padding: 0">
            <div class="unit-meta">
                <Link :href="route('lesson-planner.lesson', lesson.id)">
                    <- to Lesson
                </Link>
                <Link v-if="props.activity?.id" :href="route('activities.show', props.lesson.slug)">
                    View
                </Link>
            </div>
            <div class="featured-title l">
                Lesson {{ props.lesson.slug }}: Activity
            </div>
            <AppTip v-if="lesson.published">
                <p><b>WARNING:</b> You are editing an Activity for a published Lesson. If you intend to revert it to a
                    draft, <b>you must revert the Lesson to a draft first</b>. Otherwise, if you make any changes to the
                    Activity now that would render it unpublishable, you will not be able to save those changes.</p>
            </AppTip>
            <div class="field-item">
                <label>Title</label>
                <div class="field-input">
                    <input type="text" v-model="activity.title" placeholder="Title" required>
                </div>
            </div>

            <div class="featured-title m">blocks</div>

            <DocumentBlocksManager :document-blocks="activity.document.blocks"
                                   :block-types="['text', 'audio', 'table', 'exercises']"
            />
        </div>

        <AppTip>
            <p>The Activity is currently {{ activity.published ? 'Published' : 'a Draft' }}.</p>
            <template v-if="!isPublishable">
                <p style="font-weight: 700">The Activity cannot be Published in the current state.</p>
                <ul>
                    <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                </ul>
                <p v-if="activity.published" style="font-weight: 700">Because the Activity is already Published, the
                    current state cannot be saved except by reverting it to Draft.</p>
            </template>
        </AppTip>
        <AppTip v-if="activity.errors?.published">
            <p>{{ activity.errors.published }}</p>
        </AppTip>

        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button type="button"
                        @click="saveActivity({ publish: activity.published })"
                        :disabled="isSaving || !hasNavigationGuard || (activity.published && !isPublishable)">
                    Save
                </button>
                <button type="button" :disabled="!hasNavigationGuard" @click="activity.reset()">Reset</button>
                <button type="button"
                        @click="saveActivity({ publish: !activity.published })"
                        :disabled="isSaving || (!activity.published && !isPublishable) || (activity.published && lesson.published)"
                >
                    {{ hasNavigationGuard ? 'Save & ' : '' }} {{ activity.published ? 'Revert to Draft' : 'Publish' }}
                </button>
                <button type="button" @click="deleteActivity">Delete Activity</button>
            </div>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
