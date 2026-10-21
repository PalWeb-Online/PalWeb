<script setup>
import {route} from "ziggy-js";
import {useForm} from "../../../composables/useForm.js";
import Draggable from 'vuedraggable';
import {computed, onMounted, ref, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import {useNotifier} from "../../../composables/useNotifier.js";

const units = ref([]);
const lessons = ref([]);
const Notifier = useNotifier();

const {
    form,
    isDirty,
    defaults,
    payload,
    setErrors,
    clearErrors,
} = useForm({
    units: [],
})

onMounted(async () => {
    const {data} = await axios.get(route('lesson-planner.get'));
    units.value = data.units?.data ?? data.units ?? [];
    lessons.value = data.lessons?.data ?? data.lessons ?? [];
    form.units = [...units.value];
    defaults();
});

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
    return isDirty.value && !isSaving.value;
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
    clearErrors();

    try {
        await axios.patch(route('lesson-planner.update'), payload());
        defaults();

    } catch (error) {
        if (error?.response?.status === 422) {
            setErrors(error.response.data.errors ?? {});
        } else {
            Notifier.notify({
                message: 'Oh no! The Course could not be saved.',
                type: 'error',
            });
        }

    } finally {
        isSaving.value = false;
    }
};

const updateUnitPositions = () => {
    form.units.forEach((unit, index) => {
        unit.position = index + 1;
    });
};

watch(units, (newValue) => {
    form.units = [...newValue];
}, {deep: true});
</script>
<template>
    <div id="app-head">
        <h1>{{ $t('pages.lesson-planner.title') }}</h1>
    </div>
    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%); padding: 0">
            <div class="unit-meta">
                <RouterLink :to="{ name: 'units.index' }">
                    <- to Academy
                </RouterLink>
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
                            <RouterLink v-if="element.id" :to="{ name: 'lesson-planner.unit', params: { unit: element.id } }"
                                  class="material-symbols-rounded">edit
                            </RouterLink>
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
                            <RouterLink :to="{ name: 'lesson-planner.lesson', params: { lesson: lesson.id } }" class="material-symbols-rounded">edit
                            </RouterLink>
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
