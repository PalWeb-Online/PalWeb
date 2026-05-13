<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {computed, onMounted, provide, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import DocumentBlocksManager from "./UI/DocumentBlocksManager.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDialogSearch} from "../../../composables/useDialogSearch.js";
import {useUnitSearch} from "../../../composables/useUnitSearch.js";
import {useDeckSearch} from "../../../composables/useDeckSearch.js";
import SearchSelect from "../../../components/SearchSelect.vue";
import DeckItem from "../../../components/DeckItem.vue";
import DialogItem from "../../../components/DialogItem.vue";
import {useLessonEditor} from "../../../composables/lessons/useLessonEditor.js";
import {useLessonValidation} from "../../../composables/lessons/useLessonValidation.js";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    lessonId: {
        type: Number,
        default: null,
    },
    initialUnit: {
        type: Object,
        default: null,
    },
})

const {
    form,
    errors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    lesson,
    lessonNotFound,
    sentenceModels,
    allowedBlockTypes,
    isUnitLocked,
    selectedUnit,
    selectedDeck,
    selectedDialog,
    loadForm,
    reloadForm,
    saveLesson,
    deleteLesson,
    setSelectedUnit,
    setSelectedDeck,
    setSelectedDialog,
} = useLessonEditor({
    lessonId: computed(() => props.lessonId),
    initialUnit: computed(() => props.initialUnit),
});

const {
    searchUnits,
} = useUnitSearch();

const {
    searchDecks,
} = useDeckSearch({
    currentLessonId: computed(() => props.lessonId),
});

const {
    searchDialogs,
} = useDialogSearch({
    currentLessonId: computed(() => props.lessonId),
});

provide('documentSentenceModels', sentenceModels);

onMounted(async () => {
    await loadForm();
});

watch(() => props.lessonId, async () => {
    await reloadForm();
});

const {
    validationIssues,
    isValidRequest,
    publishIssues,
    isPublishable,
} = useLessonValidation({
    form,
    selectedDeck,
    selectedDialog,
    lessonActivity: computed(() => lesson.value?.activity ?? null),
    allowedBlockTypes,
});

const hasNavigationGuard = computed(() => isDirty.value && !isSaving.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

watch(() => form.group, (newGroup) => {
    if (newGroup === 'main') {
        form.unlock_conditions = [];
    }
});

const moveSkill = (si, direction) => {
    const arr = form.document.skills;
    const toIndex = direction === 'up' ? si - 1 : si + 1;
    if (toIndex < 0 || toIndex >= arr.length) return;

    const [item] = arr.splice(si, 1);
    arr.splice(toIndex, 0, item);
};

const addSkill = () => {
    if (form.document.skills.length === 3) return;

    form.document.skills.push({
        type: '',
        title: '',
        description: '',
        blocks: []
    });
}

const removeSkill = (i) => {
    form.document.skills.splice(i, 1)
}

const addUnlockCondition = () => {
    form.unlock_conditions.push({
        type: '',
        value: '',
    });
}

const removeUnlockCondition = (i) => {
    form.unlock_conditions.splice(i, 1)
}
</script>
<template>
    <Head :title="`Lesson Planner: Lesson ${lesson?.global_position}`"/>

    <div id="app-head">
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <div v-else-if="lessonNotFound" class="form-body" style="width: min(96rem, 100%); padding: 0">
            <p>Sorry, but the requested Lesson does not exist.</p>
            <Link :href="route('lesson-planner.index')">
                Back to Lesson Planner
            </Link>
        </div>
        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="unit-meta">
                    <Link v-if="lesson?.unit?.id || initialUnit" :href="route('lesson-planner.unit', lesson?.unit?.id ?? initialUnit.id)">
                        <- to Unit
                    </Link>
                    <Link v-if="lesson?.id" :href="route('lessons.show', lesson.global_position)">
                        View
                    </Link>
                </div>
                <div class="featured-title l">
                    <span v-if="lesson?.id">Lesson {{ lesson.global_position }}</span>
                    <span v-else-if="initialUnit?.id">Lesson in Unit {{ initialUnit?.position }}</span>
                    <span v-else>New Lesson</span>
                </div>

                <div class="field-item">
                    <label>Group</label>
                    <select v-model="form.group" required>
                        <option value="main">main</option>
                        <option value="extra">extra</option>
                    </select>
                </div>

                <div class="field-item" v-if="form.group !== 'main'">
                    <label>Unlock Conditions</label>
                    <template v-for="(condition, i) in form.unlock_conditions" :key="i">
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

<!--                selected* is only used explicitly for the SearchSelect components -->
                <SearchSelect
                    v-model="form.unit_id"
                    label="Unit"
                    :initial-title="selectedUnit?.title || ''"
                    :search="searchUnits"
                    :error="errors?.unit_id"
                    :disabled="isUnitLocked"
                    @select="setSelectedUnit"
                    @clear="setSelectedUnit()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.title }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                Unit {{ option.position }} · {{ option.lessons_count }}/9 Lessons
                                <span v-if="!option.published"> · Draft</span>
                            </div>
                        </div>
                    </template>
                </SearchSelect>

                <div class="field-item">
                    <label>Title</label>
                    <input type="text" v-model="form.title" placeholder="Title" required>
                </div>
                <div class="field-item">
                    <label>Description</label>
                    <textarea v-model="form.description"/>
                </div>

                <SearchSelect
                    v-model="form.deck_id"
                    label="Deck"
                    :initial-title="selectedDeck?.name || ''"
                    :search="searchDecks"
                    :error="errors?.deck_id"
                    @select="setSelectedDeck"
                    @clear="setSelectedDeck()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.name }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                {{ option.private ? 'Private' : 'Public' }}
                            </div>
                        </div>
                    </template>
                </SearchSelect>
                <DeckItem v-if="selectedDeck" :model="selectedDeck"/>

                <AppTip>
                    <p>If you cannot find the desired Deck, make sure it doesn't already belong to another Lesson.</p>
                </AppTip>

                <SearchSelect
                    v-model="form.dialog_id"
                    label="Dialog"
                    :initial-title="selectedDialog?.title || ''"
                    :search="searchDialogs"
                    :error="errors?.dialog_id"
                    @select="setSelectedDialog"
                    @clear="setSelectedDialog()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.title }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                {{ option.published ? 'Published' : 'Draft' }}
                            </div>
                        </div>
                    </template>
                </SearchSelect>
                <DialogItem v-if="selectedDialog" :model="selectedDialog"/>

                <div class="lesson-planner--skill" v-for="(skill, si) in form.document.skills" :key="si">
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
                                    :disabled="si === form.document.skills.length - 1">
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

                    <DocumentBlocksManager :document-blocks="form.document.skills[si].blocks"
                                           :block-types="allowedBlockTypes"
                    />
                </div>
                <button v-if="form.document.skills.length < 3" type="button" @click="addSkill">Add Skill</button>

                <Link v-if="lesson?.id" :href="route('lesson-planner.lesson-activity', lesson.id)"
                      class="portal-button" style="justify-self: center"
                >
                    {{ lesson.activity?.id ? 'Edit' : 'Create' }} Activity
                </Link>
                <AppTip v-else>
                    <p>You must create the Lesson first to create the Activity.</p>
                </AppTip>
            </div>

            <AppTip>
                <p>The Lesson is currently {{ form.published ? 'Published' : 'a Draft' }}.</p>
                <template v-if="!isValidRequest">
                    <p style="font-weight: 700">The Lesson cannot be saved in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in validationIssues" :key="i">{{ issue }}</li>
                    </ul>
                </template>
                <template v-if="!isPublishable">
                    <p style="font-weight: 700">The Lesson cannot be Published in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                    </ul>
                    <p v-if="form.published" style="font-weight: 700">Because the Lesson is already Published, the
                        current state cannot be saved except by reverting it to Draft.</p>
                </template>
                <template v-if="Object.keys(errors).length">
                    <p style="font-weight: 700">Oops — the Lesson could not be saved.</p>
                    <ul>
                        <li v-for="(error, key) in errors" :key="key">{{ key }}: {{ error }}</li>
                    </ul>
                </template>
            </AppTip>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button type="button"
                            @click="saveLesson({ publish: form.published })"
                            :disabled="isSaving || !hasNavigationGuard || !isValidRequest || (form.published && !isPublishable)">
                        Save
                    </button>
                    <button type="button" :disabled="!hasNavigationGuard" @click="reset()">Reset</button>
                    <button type="button"
                            @click="saveLesson({ publish: !form.published })"
                            :disabled="isSaving || !isValidRequest || (!form.published && !isPublishable)"
                    >
                        {{ hasNavigationGuard ? 'Save & ' : '' }} {{ form.published ? 'Revert to Draft' : 'Publish' }}
                    </button>
                    <button type="button" @click="deleteLesson">Delete Lesson</button>
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
