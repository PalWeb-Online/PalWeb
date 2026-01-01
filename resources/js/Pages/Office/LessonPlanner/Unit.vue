<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import Draggable from 'vuedraggable';
import {computed, ref, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import ToggleSingle from "../../../components/ToggleSingle.vue";
import AppTip from "../../../components/AppTip.vue";
import AppButton from "../../../components/AppButton.vue";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    unit: Object
})

const unit = useForm({
    position: props.unit?.position || null,
    title: props.unit?.title || '',
    lessons: props.unit?.lessons || [],
    published: props.unit?.published || false,
})

const addLesson = () => {
    unit.lessons.push({
        id: null,
        title: '',
        document: props.lesson?.document || {
            schemaVersion: 1,
            skills: []
        },
        unit_position: unit.lessons.length + 1,
    });
}

const removeLesson = (lesson) => {
    unit.lessons.splice(unit.lessons.indexOf(lesson), 1);
}

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return unit.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    return unit.title;
});

const saveUnit = async () => {
    isSaving.value = true;

    const method = props.unit?.id
        ? unit.patch.bind(unit)
        : unit.post.bind(unit);

    const url = props.unit?.id
        ? route('units.update', props.unit.id)
        : route('units.store');

    method(url, {
        onSuccess: () => {
            unit.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Unit could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

const deleteUnit = () => {
    if (!confirm('Are you sure you want to delete this Unit?')) return;

    router.delete(route('units.destroy', props.unit.id));
};

const updateLessonPositions = () => {
    unit.lessons.forEach((lesson, index) => {
        lesson.unit_position = index + 1;
    });
};

watch(
    () => props.unit,
    (newValue) => {
        if (newValue) {
            Object.assign(unit, newValue);
        }
    },
    {deep: true}
);
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%); padding: 0">
            <div class="unit-meta">
                <Link :href="route('lesson-planner.index')">
                    <- to Course
                </Link>
                <Link v-if="props.unit?.id" :href="route('units.show', props.unit.position)">
                    View
                </Link>
            </div>
            <div class="featured-title l">
                {{ props.unit?.id ? 'Unit ' + props.unit.position : 'New Unit' }}: {{ unit.title }}
            </div>

            <div class="field-item">
                <label>Title</label>
                <div class="field-input">
                    <input type="text" v-model="unit.title" placeholder="Title" required>
                </div>
            </div>
            <div class="featured-title m">
                Lessons
            </div>
            <p>Removing a Lesson from this list will unlink it from this Unit. You must go to its edit page to delete
                the Lesson altogether.</p>
            <draggable v-if="unit.lessons.length > 0" class="unit-lessons-draggable"
                       :list="unit.lessons" itemKey="id" handle=".handle"
                       @change="updateLessonPositions">
                <template #item="{ element, index }">
                    <li class="draggable-item" :class="{'hidden': !element.published}">
                        <span v-if="element.id" class="material-symbols-rounded" style="cursor: pointer;"
                              @click="removeLesson(element)">delete
                        </span>
                        <div>
                            <div>{{ unit.position + '0' + (index + 1) }}</div>
                            <input v-model="element.title">
                            <Link v-if="element.id" :href="route('lesson-planner.lesson', element)"
                                  class="material-symbols-rounded">edit
                            </Link>
                        </div>
                        <span class="handle material-symbols-rounded">menu</span>
                    </li>
                </template>
            </draggable>
            <button v-if="unit.lessons.length < 9" @click="addLesson">
                + Lesson
            </button>
            <ToggleSingle v-model="unit.published" label="Publish"/>
            <AppTip v-if="unit.published && (!unit.lessons || unit.lessons.filter(l => l.published).length < 9)">
                <p>This Unit is incomplete, as it contains fewer than 9 published Lessons.</p>
            </AppTip>
        </div>
        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button class="app-button" @click="saveUnit"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest">
                    save
                </button>
                <AppButton @click="deleteUnit" label="delete"/>
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
