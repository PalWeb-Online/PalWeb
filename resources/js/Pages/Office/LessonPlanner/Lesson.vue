<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {computed, onMounted, provide, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import DocumentBlocksManager from "../../../components/Blocks/Editors/DocumentBlocksManager.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDeckSearch} from "../../../composables/decks/useDeckSearch.js";
import {useDialogSearch} from "../../../composables/dialogs/useDialogSearch.js";
import {useUnitSearch} from "../../../composables/units/useUnitSearch.js";
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
    errors: backendErrors,
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
    isValidRequest,
    validationErrors,
    publishIssues,
    isPublishable,
} = useLessonValidation({
    form,
    backendErrors,
    selectedDeck,
    selectedDialog,
    lessonActivity: computed(() => lesson.value?.activity ?? null),
    allowedBlockTypes,
});

const hasNavigationGuard = computed(() => isDirty.value);
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
        <h1>{{ $t('pages.lesson-planner.title') }}</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="lessonNotFound">
            <AppTip>
                <p>{{ $t('pages.common.not-found', {model: $t('actions.models.lesson')}) }}</p>
            </AppTip>
            <Link class="portal-button" :href="route('lesson-planner.index')">
                Back to Lesson Planner
            </Link>
        </template>

        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="unit-meta">
                    <Link v-if="lesson?.unit?.id || initialUnit"
                          :href="route('lesson-planner.unit', lesson?.unit?.id ?? initialUnit.id)">
                        <- to Unit
                    </Link>
                </div>
                <div class="featured-title l">
                    <span v-if="lesson?.id">{{
                            $t('lesson.key-index', {index: lesson.global_position})
                        }}</span>
                    <span v-else-if="initialUnit?.id">Lesson in Unit {{ initialUnit?.position }}</span>
                    <span v-else>{{ $t('forms.actions.create', {title: $t('actions.models.lesson')}) }}</span>
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

                <SearchSelect
                    v-model="form.unit_id"
                    :label="$t('actions.models.unit')"
                    :initial-title="selectedUnit?.title || ''"
                    :search="searchUnits"
                    :error="validationErrors[`unit_id`]"
                    :disabled="isUnitLocked"
                    @select="setSelectedUnit"
                    @clear="setSelectedUnit()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.title }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                {{ $t('unit.number', {number: option.position})}}
                                · {{ option.lessons_count }}/9 {{ $t('models.lessons') }}
                                <span v-if="!option.published"> · {{ $t('forms.status.draft') }}</span>
                            </div>
                        </div>
                    </template>
                </SearchSelect>

                <div class="field-item">
                    <label>{{ $t('forms.fields.title') }}</label>
                    <input type="text" v-model="form.title" placeholder="Title" required>
                </div>
                <div class="field-item">
                    <label>{{ $t('forms.fields.description') }}</label>
                    <textarea v-model="form.description"/>
                </div>

                <SearchSelect
                    v-model="form.deck_id"
                    :label="$t('actions.models.deck')"
                    :initial-title="selectedDeck?.name || ''"
                    :search="searchDecks"
                    :error="validationErrors[`deck_id`]"
                    @select="setSelectedDeck"
                    @clear="setSelectedDeck()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.name }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                {{ option.private ? $t('forms.status.private') : $t('forms.status.public') }}
                            </div>
                        </div>
                    </template>
                </SearchSelect>
                <DeckItem v-if="selectedDeck" :model="selectedDeck"/>

                <AppTip>
                    <p>{{ $t('pages.lesson-planner.messages.info-deck-search') }}</p>
                </AppTip>

                <SearchSelect
                    v-model="form.dialog_id"
                    :label="$t('actions.models.dialog')"
                    :initial-title="selectedDialog?.title || ''"
                    :search="searchDialogs"
                    :error="validationErrors[`dialog_id`]"
                    @select="setSelectedDialog"
                    @clear="setSelectedDialog()"
                >
                    <template #option="{ option }">
                        <div>
                            <strong>{{ option.title }}</strong>
                            <div style="font-size: 0.85em; opacity: 0.7">
                                {{ option.published ? $t('forms.status.published') : $t('forms.status.draft') }}
                            </div>
                        </div>
                    </template>
                </SearchSelect>
                <DialogItem v-if="selectedDialog" :model="selectedDialog"/>

                <div class="lesson-planner--skill" v-for="(skill, si) in form.document.skills" :key="si">
                    <div class="field-item">
                        <div class="block-meta">
                            <div class="featured-title m" style="flex-grow: 1">
                                {{ $t('skill.key-index', {index: si + 1}) }}
                            </div>
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
                    {{
                        lesson.activity?.id
                            ? $t('forms.actions.edit', {title: $t('actions.models.activity')})
                            : $t('forms.actions.create', {title: $t('actions.models.activity')})
                    }}
                </Link>
                <AppTip v-else>
                    <p>You must create the Lesson first to create the Activity.</p>
                </AppTip>

                <AppTip>
                    <p>{{
                            $t('forms.messages.current-status', {
                                model: $t('actions.models.lesson'),
                                status: form.published ? $t('forms.status.published') : $t('forms.status.draft')
                            })
                        }}</p>
                    <template v-if="Object.keys(validationErrors).length">
                        <p style="font-weight: 700">{{
                                $t('forms.messages.has-validation-errors', {model: $t('actions.models.lesson')})
                            }}</p>
                        <ul>
                            <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                        </ul>
                    </template>
                    <template v-if="!isPublishable">
                        <p style="font-weight: 700">
                            {{ $t('forms.messages.has-publish-issues', {model: $t('actions.models.lesson')}) }}</p>
                        <ul>
                            <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                        </ul>
                        <p v-if="form.published" style="font-weight: 700">Because the Lesson is already Published, the
                            current state cannot be saved except by reverting it to Draft.</p>
                    </template>
                </AppTip>
            </div>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button
                        type="button"
                        @click="saveLesson({ publish: form.published })"
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
                    <button
                        type="button"
                        @click="saveLesson({ publish: !form.published })"
                        :disabled="isSaving || !isValidRequest || (!form.published && !isPublishable)"
                    >
                        {{
                            hasNavigationGuard ? $t('forms.actions.save') + ' & ' : ''
                        }} {{
                            form?.published ? $t('forms.actions.set-status.draft') : $t('forms.actions.set-status.published')
                        }}
                    </button>
                    <button type="button" @click="deleteLesson()">
                        {{ $t('actions.common.delete', {model: $t('actions.models.lesson')}) }}
                    </button>
                    <Link v-if="lesson?.id" :href="route('lessons.show', lesson.global_position)">
                        {{ $t('actions.common.view', {model: $t('actions.models.lesson')}) }}
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

<style scoped lang="scss">
.lesson-planner--skill {
    display: grid;
    gap: 3.2rem;
    border-block-start: 0.8rem solid var(--color-accent-medium);
    padding-block: 3.2rem;
}

</style>
