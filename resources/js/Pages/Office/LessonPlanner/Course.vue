<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {useForm} from "@inertiajs/vue3";
import Draggable from 'vuedraggable';
import {computed, ref, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";

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

const validationIssues = computed(() => {
    const issues = [];

    form.units.forEach((unit, index) => {
        const name = `Unit ${index + 1}`;

        if (!unit.title) {
            issues.push(`${name}: Title is required.`);
        }
    });

    return issues;
});

const isValidRequest = computed(() => validationIssues.value.length === 0);

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
        <h1>{{ $t('pages.lesson-planner.title') }}</h1>
    </div>
    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%); padding: 0">
            <div class="unit-meta">
                <Link :href="route('units.index')">
                    <- to Academy
                </Link>
            </div>
            <div class="featured-title l">
                {{ $t('pages.lesson-planner.course') }}
            </div>
            <div class="featured-title m">
                {{ $t('models.units') }}
            </div>
            <p>{{ $t('pages.lesson-planner.messages.info-unit-removal') }}</p>
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
            <div class="block-add-buttons">
                <div>
                    <div class="add-button" @click="addUnit">+</div>
                    <div>{{ $t('actions.models.unit') }}</div>
                </div>
            </div>

            <template v-if="lessons.length">
                <div class="featured-title m">
                    {{ $t('models.lessons') }}
                </div>
                <ul class="unit-lessons-draggable">
                    <li v-for="lesson in lessons" class="draggable-item" :class="{'hidden': !lesson.published}">
                        <div>
                            <div>{{ lesson.id }}</div>
                            <div style="width: 100%">{{ lesson.title }}</div>
                            <Link :href="route('lesson-planner.lesson', lesson)" class="material-symbols-rounded">edit
                            </Link>
                        </div>
                    </li>
                </ul>
            </template>
        </div>

        <AppTip v-if="!isValidRequest">
            <p style="font-weight: 700">{{ $t('forms.messages.has-validation-errors', {model: $t('pages.lesson-planner.course')}) }}</p>
            <ul>
                <li v-for="(issue, i) in validationIssues" :key="i">{{ issue }}</li>
            </ul>
        </AppTip>
        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button
                    type="button"
                    @click="saveCourse"
                    :disabled="isSaving || !hasNavigationGuard || !isValidRequest"
                >
                    {{ $t('forms.actions.save') }}
                </button>
            </div>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="$t('modals.nav-guard.messages.unsaved-changes')"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
