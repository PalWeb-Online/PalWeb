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
    errors,
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

const hasNavigationGuard = computed(() => isDirty.value && !isSaving.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const validationIssues = computed(() => {
    const issues = [];

    if (!form.title) {
        issues.push('Title is required.');
    }

    form.lessons.forEach((lesson, index) => {
        const name = `Lesson ${form.position}0${index + 1}`;

        if (!lesson.title) {
            issues.push(`${name}: Title is required.`);
        }
    });

    return issues;
});

const isValidRequest = computed(() => validationIssues.value.length === 0);

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
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <div v-else-if="unitNotFound" class="form-body" style="width: min(96rem, 100%); padding: 0">
            <p>Sorry, but the requested Lesson does not exist.</p>
            <Link :href="route('lesson-planner.index')">
                Back to Lesson Planner
            </Link>
        </div>
        <template v-else>
            <div class="form-body" style="width: min(96rem, 100%); padding: 0">
                <div class="unit-meta">
                    <Link :href="route('lesson-planner.index')">
                        <- to Course
                    </Link>
                    <Link v-if="unit?.id" :href="route('units.show', unit.position)">
                        View
                    </Link>
                </div>
                <div class="featured-title l">
                    {{ unit?.id ? 'Unit ' + unit.position : 'New Unit' }}: {{ form.title }}
                </div>

                <div class="field-item">
                    <label>Title</label>
                    <div class="field-input">
                        <input type="text" v-model="form.title" placeholder="Title" required>
                    </div>
                </div>
                <div class="featured-title m">
                    Lessons
                </div>
                <p>Removing a Lesson from this list will unlink it from this Unit. You must go to its edit page to
                    delete
                    the Lesson altogether.</p>
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
                <button v-if="form.lessons.length < 9" @click="addLesson">
                    + Lesson
                </button>
                <AppTip v-if="form.published && (!form.lessons || form.lessons.filter(l => l.published).length < 9)">
                    <p>This Unit is incomplete, as it contains fewer than 9 published Lessons.</p>
                </AppTip>
            </div>

            <AppTip>
                <p>The Unit is currently {{ form.published ? 'Published' : 'a Draft' }}.</p>

                <template v-if="!isValidRequest">
                    <p style="font-weight: 700">The Unit cannot be saved in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in validationIssues" :key="i">{{ issue }}</li>
                    </ul>
                </template>
                <template v-if="Object.keys(errors).length">
                    <p style="font-weight: 700">Oops — the Unit could not be saved.</p>
                    <ul>
                        <li v-for="(error, key) in errors" :key="key">{{ key }}: {{ error }}</li>
                    </ul>
                </template>
            </AppTip>

            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <button type="button"
                            @click="saveUnit({ publish: form.published })"
                            :disabled="isSaving || !hasNavigationGuard || !isValidRequest">
                        Save
                    </button>
                    <button type="button" :disabled="!hasNavigationGuard" @click="reset()">Reset</button>
                    <button type="button"
                            @click="saveUnit({ publish: !form.published })"
                            :disabled="isSaving || !isValidRequest"
                    >
                        {{ hasNavigationGuard ? 'Save & ' : '' }} {{ form.published ? 'Revert to Draft' : 'Publish' }}
                    </button>
                    <button type="button" @click="deleteUnit()">Delete Unit</button>
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
