<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {useForm} from "@inertiajs/vue3";
import Draggable from 'vuedraggable';
import {computed, ref, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    units: Object,
    lessons: Object
})

const form = useForm({
    units: props.units || [],
})

const addUnit = () => {
    form.units.push({
        id: null,
        title: '',
        position: form.units.length + 1,
    });
    updateUnitPositions();
}

const removeUnit = (unit) => {
    form.units.splice(form.units.indexOf(unit), 1);
}

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return form.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    return form.units.every(unit => unit.title.trim().length > 0);
});

const saveCourse = async () => {
    isSaving.value = true;

    form.patch(route('lesson-planner.update'), {
        onSuccess: () => {
            form.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Course could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

const updateUnitPositions = () => {
    form.units.forEach((unit, index) => {
        unit.position = index + 1;
    });
};

watch(
    () => props.units,
    (newValue) => {
        if (newValue) {
            Object.assign(form.units, newValue);
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
                <Link :href="route('units.index')">
                    <- to Academy
                </Link>
            </div>
            <div class="featured-title l">
                Course
            </div>

            <div class="featured-title m">
                Units
            </div>
            <p>You must go to the edit page for a Unit to delete it.</p>
            <draggable v-if="form.units.length > 0" class="unit-lessons-draggable"
                       :list="form.units" itemKey="id" handle=".handle"
                       @change="updateUnitPositions">
                <template #item="{ element }">
                    <li class="draggable-item" :class="{'hidden': !element.published}">
                        <span v-if="!element.id" class="material-symbols-rounded" style="cursor: pointer;"
                              @click="removeUnit(element)">delete
                        </span>
                        <div>
                            <div>{{ element.position }}</div>
                            <input v-model="element.title">
                            <Link v-if="element.id" :href="route('lesson-planner.unit', element.id)"
                                  class="material-symbols-rounded">edit
                            </Link>
                        </div>
                        <span class="handle material-symbols-rounded">drag_indicator</span>
                    </li>
                </template>
            </draggable>
            <button @click="addUnit">
                + Unit
            </button>

            <template v-if="lessons.length">
                <div class="featured-title m">
                    Lessons
                </div>
                <div class="unit-lessons-draggable">
                    <li v-for="lesson in lessons" class="draggable-item" :class="{'hidden': !lesson.published}">
                        <div>
                            <div>{{ lesson.id }}</div>
                            <div style="width: 100%">{{ lesson.title }}</div>
                            <Link :href="route('lesson-planner.lesson', lesson)" class="material-symbols-rounded">edit
                            </Link>
                        </div>
                    </li>
                </div>
            </template>
        </div>
        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button class="app-button" @click="saveCourse"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest">
                    save
                </button>
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
