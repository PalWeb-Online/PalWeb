<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import LessonContentSearchBar from "./UI/LessonContentSearchBar.vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import DocumentBlocksManager from "./UI/DocumentBlocksManager.vue";

defineOptions({
    layout: Layout,
})

const NotificationStore = useNotificationStore();

const props = defineProps({
    lesson: Object
})

const lesson = useForm({
    unit_id: props.lesson?.unit?.id || null,
    title: props.lesson?.title || '',
    document: props.lesson?.document || {
        schemaVersion: 1,
        skills: []
    },
    deck_id: props.lesson?.deck?.id || null,
    dialog_id: props.lesson?.dialog?.id || null,
    published: props.lesson?.published || false,
});

const addSkill = () => {
    if (lesson.document.skills.length === 3) return;

    lesson.document.skills.push({
        type: '',
        title: '',
        description: '',
        blocks: []
    });
}

const removeSkill = (index) => {
    lesson.document.skills.splice(index, 1)
}

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return lesson.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const saveLesson = async ({publish}) => {
    isSaving.value = true;
    lesson.published = !!publish

    const method = props.lesson?.id
        ? lesson.patch.bind(lesson)
        : lesson.post.bind(lesson);

    const url = props.lesson?.id
        ? route('lessons.update', props.lesson.id)
        : route('lessons.store');

    method(url, {
        onSuccess: () => {
            lesson.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Lesson could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

const deleteLesson = () => {
    if (!confirm('Are you sure you want to delete this Lesson?')) return;

    router.delete(route('lessons.destroy', props.lesson.id));
};

const isNonEmptyString = (v) => typeof v === 'string' && v.trim().length > 0;

const publishIssues = computed(() => {
    const issues = [];

    const skills = lesson.document.skills || [];

    if (skills.length === 0) {
        issues.push('At least one Skill is required.');
    }

    skills.forEach((skill, si) => {
        const skillName = `Skill ${si + 1}`;

        if (!isNonEmptyString(skill.type)) issues.push(`${skillName}: Type is required.`);
        if (!isNonEmptyString(skill.title)) issues.push(`${skillName}: Title is required.`);
        if (!isNonEmptyString(skill.description)) issues.push(`${skillName}: Description is required.`);

        if (skill.blocks.length === 0) {
            issues.push(`${skillName}: Skill must have at least one Block.`);
        }

        skill.blocks.forEach((block, bi) => {
            const where = `${skillName}, Block ${bi + 1} (${block.type})`;

            if (block.type === 'text') {
                if (!isNonEmptyString(block.content)) {
                    issues.push(`${where}: Text Block content cannot be empty.`);
                }
            }

            if (block.type === 'sentence') {
                if (!block.model && !block.custom) {
                    issues.push(`${where}: Sentence Block must have Sentence model or custom Sentence.`);

                } else if (block.custom) {
                    if (!isNonEmptyString(block.custom.transl)) {
                        issues.push(`${where}: Translation cannot be empty.`);
                    }

                    if (!block.custom.terms || block.custom.terms.length === 0) {
                        issues.push(`${where}: Sentence must have at least one Term.`);

                    } else {
                        block.custom.terms.forEach((term, ti) => {
                            if (!isNonEmptyString(term.term) || !isNonEmptyString(term.transc)) {
                                issues.push(`${where}: Term ${ti + 1} must have both Arabic text and transcription.`);
                            }
                        });
                    }
                }
            }

            if (block.type === 'chart') {
                const rows = block.rows || [];
                if (rows.length === 0) {
                    issues.push(`${where}: Chart Block must have at least one row.`);
                } else {
                    rows.forEach((row, ri) => {
                        row.items.forEach((item, ii) => {
                            if (!isNonEmptyString(item.key) || !isNonEmptyString(item.ar) || !isNonEmptyString(item.tr)) {
                                issues.push(`${where}: Row ${ri + 1}, Item ${ii + 1} is missing required values (key, ar, or tr).`);
                            }
                        });
                    });
                }
            }
        });
    });

    if (!lesson.unit_id) issues.push('Lesson must be attached to a Unit.');
    if (!lesson.deck_id) issues.push('Lesson must have an assigned Deck.');
    if (!lesson.dialog_id) issues.push('Lesson must have an assigned Dialog.');

    if (!props.lesson.activity?.id) {
        issues.push('Lesson must have an associated Activity.');

    } else if (!props.lesson.activity.published) {
        issues.push('The associated Activity must be published before the Lesson can be published.');
    }

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
                <Link v-if="props.lesson.unit?.id" :href="route('lesson-planner.unit', props.lesson.unit?.id)">
                    <- to Unit
                </Link>
                <Link v-if="props.lesson?.id" :href="route('lessons.show', props.lesson.slug)">
                    View
                </Link>
            </div>
            <div class="featured-title l">
                {{ props.lesson?.id ? 'Lesson ' + props.lesson.slug : 'New Lesson' }}
            </div>

            <LessonContentSearchBar
                v-model="lesson.unit_id"
                type="unit"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.unit?.title || ''"
                :errors="lesson.errors"
            />

            <div class="field-item">
                <label>Title</label>
                <input type="text" v-model="lesson.title" placeholder="Title" required>
            </div>

            <LessonContentSearchBar
                v-model="lesson.deck_id"
                type="deck"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.deck?.name || ''"
            />

            <div v-for="(skill, si) in lesson.document.skills" :key="si">
                <div class="field-item">
                    <div class="featured-title m">Skill {{ si + 1 }}</div>
                    <button type="button" @click="removeSkill(si)">Remove Skill</button>
                    <input type="text" v-model="skill.type" placeholder="type" required>
                    <input type="text" v-model="skill.title" placeholder="title" required>
                    <input type="text" v-model="skill.description" placeholder="description" required>
                </div>

                <DocumentBlocksManager :document-blocks="lesson.document.skills[si].blocks"
                                       :block-types="['text', 'chart', 'sentence']"
                />
            </div>
            <button v-if="lesson.document.skills.length < 3" type="button" @click="addSkill">Add Skill</button>

            <Link v-if="props.lesson?.id" :href="route('lesson-planner.lesson-activity', props.lesson.id)">
                -> to Activity
            </Link>
            <AppTip v-else>
                <p>You must create the Lesson first to create the Activity.</p>
            </AppTip>

            <LessonContentSearchBar
                v-model="lesson.dialog_id"
                type="dialog"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.dialog?.title || ''"
            />
        </div>

        <AppTip>
            <p>The Lesson is currently {{ lesson.published ? 'Published' : 'a Draft' }}.</p>

            <template v-if="!isPublishable">
                <p style="font-weight: 700">The Lesson cannot be Published in the current state.</p>
                <ul>
                    <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                </ul>
                <p v-if="lesson.published" style="font-weight: 700">Because the Lesson is already Published, the
                    current state cannot be saved except by reverting it to Draft.</p>
            </template>
        </AppTip>

        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button type="button"
                        @click="saveLesson({ publish: lesson.published })"
                        :disabled="isSaving || !hasNavigationGuard || (lesson.published && !isPublishable)">
                    Save
                </button>
                <button type="button" :disabled="!hasNavigationGuard" @click="lesson.reset()">Reset</button>
                <button type="button"
                        @click="saveLesson({ publish: !lesson.published })"
                        :disabled="isSaving || (!lesson.published && !isPublishable)"
                >
                    {{ hasNavigationGuard ? 'Save & ' : '' }} {{ lesson.published ? 'Revert to Draft' : 'Publish' }}
                </button>
                <button type="button" @click="deleteLesson">Delete Lesson</button>
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
