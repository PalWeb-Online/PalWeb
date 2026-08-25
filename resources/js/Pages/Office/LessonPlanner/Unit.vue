<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import Draggable from 'vuedraggable';
import {computed, onMounted, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import {useUnitEditor} from "../../../composables/units/useUnitEditor.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useUnitValidation} from "../../../composables/units/useUnitValidation.js";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    unitId: {
        type: Number,
        default: null,
    }
})

const {
    form,
    errors: backendErrors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    unit,
    unitNotFound,
    loadForm,
    reloadForm,
    saveUnit,
    deleteUnit,
} = useUnitEditor({
    unitId: computed(() => props.unitId),
});

onMounted(async () => {
    await loadForm();
});

watch(() => props.unitId, async () => {
    await reloadForm();
});

const {
    isValidRequest,
    validationErrors,
} = useUnitValidation({
    form,
    backendErrors,
});

const hasNavigationGuard = computed(() => isDirty.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const updateLessonPositions = () => {
    form.lessons.forEach((lesson, index) => {
        lesson.unit_position = index + 1;
    });
};

const addLesson = () => {
    form.lessons.push({
        id: null,
        title: '',
        document: {
            schemaVersion: 1,
            skills: []
        },
        unit_position: form.lessons.length + 1,
    });
}

const removeLesson = (lesson) => {
    form.lessons.splice(form.lessons.indexOf(lesson), 1);
}
</script>
<template>
    <Head :title="`Lesson Planner: Unit ${unit?.position}`"/>

    <div id="app-head">
        <h1>{{ $t('pages.lesson-planner.title') }}</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="unitNotFound">
            <AppTip>
                <p>{{ $t('pages.common.not-found', {model: $t('actions.models.unit')}) }}</p>
            </AppTip>
            <Link class="portal-button" :href="route('lesson-planner.index')">
                Back to Lesson Planner
            </Link>
        </template>

        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="unit-meta">
                    <Link :href="route('lesson-planner.index')">
                        <- to Course
                    </Link>
                </div>
                <div class="featured-title l">
                    {{ unit?.id ? $t('unit.number', {number: unit.position}) : 'New Unit' }}: {{ form.title }}
                </div>

                <div class="field-item">
                    <label>{{ $t('forms.fields.title') }}</label>
                    <div class="field-input">
                        <input type="text" v-model="form.title" placeholder="Title" required>
                    </div>
                </div>
                <div class="featured-title m">
                    {{ $t('models.lessons') }}
                </div>
                <p>{{ $t('pages.lesson-planner.messages.info-lesson-removal') }}</p>
                <draggable v-if="form.lessons.length > 0" class="unit-lessons-draggable"
                           :list="form.lessons" itemKey="id" handle=".handle"
                           @change="updateLessonPositions">
                    <template #item="{ element, index }">
                        <li class="draggable-item" :class="{'hidden': !element.published}">
                        <span v-if="element.id" class="material-symbols-rounded" style="cursor: pointer;"
                              @click="removeLesson(element)">delete
                        </span>
                            <div>
                                <div>{{ form.position + '0' + (index + 1) }}</div>
                                <input v-model="element.title">
                                <Link v-if="element.id" :href="route('lesson-planner.lesson', element)"
                                      class="material-symbols-rounded">edit
                                </Link>
                            </div>
                            <span class="handle material-symbols-rounded">drag_indicator</span>
                        </li>
                    </template>
                </draggable>
                <div v-if="form.lessons.length < 9" class="block-add-buttons">
                    <div>
                        <div class="add-button" @click="addLesson">+</div>
                        <div>{{ $t('actions.models.lesson') }}</div>
                    </div>
                </div>
                <AppTip v-if="form.published && (!form.lessons || form.lessons.filter(l => l.published).length < 9)">
                    <p>{{ $t('pages.lesson-planner.messages.warning-incomplete') }}</p>
                </AppTip>
            </div>

            <Link v-if="unit" :href="route('lesson-planner.unit-decks', unit.id)">Manage Decks</Link>

            <AppTip>
                <p>{{
                        $t('forms.messages.current-status', {
                            model: $t('actions.models.unit'),
                            status: form.published ? $t('forms.status.published') : $t('forms.status.draft')
                        })
                    }}</p>
                <template v-if="Object.keys(validationErrors).length">
                    <p style="font-weight: 700">{{ $t('forms.messages.has-publish-issues', {model: $t('actions.models.unit')}) }}</p>
                    <ul>
                        <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                    </ul>
                </template>
            </AppTip>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button
                        type="button"
                        @click="saveUnit({ publish: form.published })"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest"
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
                            @click="saveUnit({ publish: !form.published })"
                            :disabled="isSaving || !isValidRequest"
                    >
                        {{
                            hasNavigationGuard ? $t('forms.actions.save') + ' & ' : ''
                        }} {{
                            form?.published ? $t('forms.actions.set-status.draft') : $t('forms.actions.set-status.published')
                        }}
                    </button>
                    <button type="button" @click="deleteUnit()">
                        {{ $t('actions.common.delete', {model: $t('actions.models.unit')}) }}
                    </button>
                    <Link v-if="unit?.id" :href="route('units.show', unit.position)">
                        {{ $t('actions.common.view', {model: $t('actions.models.unit')}) }}
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
