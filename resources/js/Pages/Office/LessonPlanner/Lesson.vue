<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import {computed, onMounted, provide, ref, watch} from "vue";
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

const lessonSentences = ref({});
const isLoadingSentences = ref(true);

provide('lessonSentences', lessonSentences);

const loadSkillSentences = async () => {
    const ids = new Set();
    props.lesson.document?.skills.forEach(skill => {
        skill.blocks.forEach(block => {
            if (block.type === 'sentence' && block.model?.id) {
                ids.add(block.model.id);
            }
        });
    });

    if (ids.size === 0) {
        isLoadingSentences.value = false;
        return;
    }

    try {
        const response = await axios.post(route('sentences.get-many'), {
            ids: Array.from(ids)
        });
        lessonSentences.value = response.data;

    } catch (error) {
        console.error("Failed to fetch Skill Sentences", error);

    } finally {
        isLoadingSentences.value = false;
    }
}

onMounted(loadSkillSentences);

const lesson = useForm({
    group: props.lesson?.group || 'main',
    unit_id: props.lesson?.unit?.id || null,
    title: props.lesson?.title || '',
    description: props.lesson?.description || '',
    document: props.lesson?.document || {
        schemaVersion: 1,
        skills: []
    },
    deck_id: props.lesson?.deck?.id || null,
    dialog_id: props.lesson?.dialog?.id || null,
    unlock_conditions: props.lesson?.unlock_conditions || [],
    published: props.lesson?.published || false,
});

watch(() => lesson.group, (newGroup) => {
    if (newGroup === 'main') {
        lesson.unlock_conditions = {};
    }
});

const uid = () => (globalThis.crypto?.randomUUID?.() ?? `id_${Date.now()}_${Math.random().toString(16).slice(2)}`);
lesson.document.skills.forEach(s => { s.id ??= uid(); });

const moveSkill = (si, direction) => {
    const arr = lesson.document.skills;
    const toIndex = direction === 'up' ? si - 1 : si + 1;
    if (toIndex < 0 || toIndex >= arr.length) return;

    const [item] = arr.splice(si, 1);
    arr.splice(toIndex, 0, item);
};

const addSkill = () => {
    if (lesson.document.skills.length === 3) return;

    lesson.document.skills.push({
        type: '',
        title: '',
        description: '',
        blocks: []
    });
}

const removeSkill = (i) => {
    lesson.document.skills.splice(i, 1)
}

const addUnlockCondition = () => {
    lesson.unlock_conditions.push({
        type: '',
        value: '',
    });
}

const removeUnlockCondition = (i) => {
    lesson.unlock_conditions.splice(i, 1)
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

const validateBlocks = (blocks, issues, path) => {
    blocks.forEach((block, bi) => {
        const where = `${path}, Block ${bi + 1} (${block.type})`;

        if (block.type === 'container') {
            if (!block.blocks || block.blocks.length === 0) {
                issues.push(`${where}: Container cannot be empty.`);
            } else {
                validateBlocks(block.blocks, issues, where);
            }
        }

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
};

const publishIssues = computed(() => {
    const issues = [];

    if (lesson.group === 'extra') {
        const conditions = lesson.unlock_conditions || [];

        if (conditions.length === 0) {
            issues.push('Extra Lessons must have at least one Unlock Condition.');
        }

        conditions.forEach((condition, i) => {
            const name = `Unlock Condition ${i + 1}`;
            if (!isNonEmptyString(condition.type)) {
                issues.push(`${name}: Type is required.`);
            }
            if (!condition.value) {
                issues.push(`${name}: Value is required.`);
            }
        });
    }

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

        } else {
            validateBlocks(skill.blocks, issues, skillName);
        }
    });

    if (!props.lesson.deck?.id && !lesson.deck_id) {
        issues.push('Lesson must have an assigned Deck.');

    } else if (props.lesson.deck && props.lesson.deck?.private) {
        issues.push('The Deck must be public before the Lesson can be published.');
    }

    if (!props.lesson.dialog?.id && !lesson.dialog_id) {
        issues.push('Lesson must have an assigned Dialog.');

    } else if (props.lesson.dialog && !props.lesson.dialog?.published) {
        issues.push('The Dialog must be published before the Lesson can be published.');
    }

    if (!props.lesson.activity?.id) {
        issues.push('Lesson must have an assigned Activity.');

    } else if (!props.lesson.activity.published) {
        issues.push('The Activity must be published before the Lesson can be published.');
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
                <Link v-if="props.lesson?.id" :href="route('lessons.show', props.lesson.global_position)">
                    View
                </Link>
            </div>
            <div class="featured-title l">
                <span v-if="props.lesson?.id">Lesson {{ props.lesson.global_position }}</span>
                <span v-else-if="props.lesson.unit.id">Lesson in Unit {{ props.lesson.unit.position }}</span>
                <span v-else>New Lesson</span>
            </div>

            <div class="field-item">
                <label>Group</label>
                <select v-model="lesson.group" required>
                    <option value="main">main</option>
                    <option value="extra">extra</option>
                </select>
            </div>

            <div class="field-item" v-if="lesson.group !== 'main'">
                <label>Unlock Conditions</label>
                <template v-for="(condition, i) in lesson.unlock_conditions" :key="i">
                    <select v-model="condition.type" required>
                        <option value="after_lesson_id">After Lesson ID</option>
                        <option value="after_lesson_position">After Lesson Position</option>
                        <option value="after_unit_id">After Unit ID</option>
                        <option value="after_unit_position">After Unit Position</option>
                    </select>
                    <!--                    todo: enable searching for units & lessons for ID retrieval-->
                    <input type="text" v-model="condition.value" placeholder="Value" required>
                    <button @click="removeUnlockCondition(i)">Remove</button>
                </template>
                <button @click="addUnlockCondition">Add</button>
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
            <div class="field-item">
                <label>Description</label>
                <textarea v-model="lesson.description"/>
            </div>

            <LessonContentSearchBar
                v-model="lesson.deck_id"
                type="deck"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.deck?.name || ''"
            />
            <AppTip>
                <p>If you cannot find the desired Deck, make sure it doesn't already belong to another Lesson. Also,
                    make sure the Deck is not set to private, as private Decks will still appear in the search.</p>
            </AppTip>

            <LessonContentSearchBar
                v-model="lesson.dialog_id"
                type="dialog"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.dialog?.title || ''"
            />
            <Link v-if="lesson.dialog_id" :href="route('dialogs.show', lesson.dialog_id)">View</Link>

            <div class="lesson-planner--skill" v-for="(skill, si) in lesson.document.skills" :key="skill.id">
                <div class="field-item">
                    <div class="block-meta">
                        <div class="featured-title m" style="flex-grow: 1">Skill {{ si + 1 }}</div>
                        <button type="button"
                                class="material-symbols-rounded"
                                @click="moveSkill(si, 'up')"
                                :disabled="si === 0">
                            move_up
                        </button>
                        <button type="button"
                                class="material-symbols-rounded"
                                @click="moveSkill(si, 'down')"
                                :disabled="si === lesson.document.skills.length - 1">
                            move_down
                        </button>
                        <button type="button" @click="removeSkill(si)"
                                class="material-symbols-rounded">
                            delete
                        </button>
                    </div>
                    <input type="text" v-model="skill.type" placeholder="type" required>
                    <input type="text" v-model="skill.title" placeholder="title" required>
                    <input type="text" v-model="skill.description" placeholder="description" required>
                </div>

                <DocumentBlocksManager :document-blocks="lesson.document.skills[si].blocks"
                                       :block-types="['container', 'text', 'chart', 'sentence']"
                />
            </div>
            <button v-if="lesson.document.skills.length < 3" type="button" @click="addSkill">Add Skill</button>

            <Link v-if="props.lesson?.id" :href="route('lesson-planner.lesson-activity', props.lesson.id)"
                  class="portal-button" style="justify-self: center"
            >
                {{ props.lesson.activity?.id ? 'Edit' : 'Create' }} Activity
            </Link>
            <AppTip v-else>
                <p>You must create the Lesson first to create the Activity.</p>
            </AppTip>
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
