<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import ToggleSingle from "../../../components/ToggleSingle.vue";
import AppTip from "../../../components/AppTip.vue";
import LessonContentSearchBar from "./UI/LessonContentSearchBar.vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import AppButton from "../../../components/AppButton.vue";

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
    skills: props.lesson?.skills || [],
    deck_id: props.lesson?.deck?.id || null,
    dialog_id: props.lesson?.dialog?.id || null,
    published: props.lesson?.published || false,
})

const addSkill = () => {
    lesson.skills.push({
        type: '',
        title: '',
        description: '',
    });
};

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return lesson.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    return lesson.title && ! (! lesson.unit_id && lesson.published);
});

const saveLesson = async () => {
    isSaving.value = true;

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
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%)">
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
            <div v-for="(skill, index) in lesson.skills" :key="index">
                <div class="field-item">
                    <label>Skill {{ index + 1 }}</label>
                    <input type="text" v-model="skill.type" placeholder="type" required>
                    <input type="text" v-model="skill.title" placeholder="title" required>
                    <input type="text" v-model="skill.description" placeholder="description" required>
                    <button @click="lesson.skills.splice(index, 1)">- Skill</button>
                </div>
            </div>
            <button @click="addSkill">+ Skill</button>

            <LessonContentSearchBar
                v-model="lesson.deck_id"
                type="deck"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.deck?.name || ''"
            />

            <LessonContentSearchBar
                v-model="lesson.dialog_id"
                type="dialog"
                :lesson-id="props.lesson?.id || null"
                :initial-title="props.lesson?.dialog?.title || ''"
            />
        </div>

        <ToggleSingle v-model="lesson.published" label="Publish"/>
        <AppTip v-if="lesson.published && (!lesson.unit_id || !lesson.deck_id || !lesson.dialog_id || lesson.skills.length < 3)">
            <p>This Lesson is incomplete.
                <span v-if="!lesson.deck_id">It has no assigned Deck. </span>
                <span v-if="!lesson.dialog_id">It has no assigned Dialog. </span>
                <span v-if="lesson.skills.length < 3">It has fewer than 3 Skills. </span>
                <span v-if="!lesson.unit_id">It is not attached to any Unit, so it may not be published. </span>
            </p>
        </AppTip>

        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button class="app-button" @click="saveLesson"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest">
                    save
                </button>
                <AppButton @click="deleteLesson" label="delete"/>
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
