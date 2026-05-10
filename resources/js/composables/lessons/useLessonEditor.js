import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {useLessonLoader} from "./useLessonLoader.js";
import {getDocumentPreset} from "../../components/Blocks/documentPresets.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";

export function useLessonEditor({
                                    lessonId = null,
                                    initialUnit = null,
                                } = {}) {
    const NotificationStore = useNotificationStore();
    const documentPreset = getDocumentPreset('lesson');
    const lessonLoader = useLessonLoader();

    const isUnitLocked = computed(() => !!initialUnit.value?.id);

    const selectedUnit = ref(initialUnit.value);
    const selectedDeck = ref(null);
    const selectedDialog = ref(null);

    const setSelectedUnit = (unit = null) => {
        if (isUnitLocked.value) {
            selectedUnit.value = initialUnit.value;
            editor.form.unit_id = initialUnit.value?.id ?? null;
            return;
        }

        selectedUnit.value = unit;
    };

    const setSelectedDeck = (deck = null) => {
        selectedDeck.value = deck;
    };

    const setSelectedDialog = (dialog = null) => {
        selectedDialog.value = dialog;
    };

    // const ensureLessonSkillIds = (document = null) => {
    //     const uid = () => (
    //         globalThis.crypto?.randomUUID?.()
    //         ?? `id_${Date.now()}_${Math.random().toString(16).slice(2)}`
    //     );
    //
    //     document?.skills?.forEach((skill) => {
    //         skill.id ??= uid();
    //     });
    // };

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        lessonLoader.setLesson(model);

        selectedUnit.value = model?.unit ?? null;
        selectedDeck.value = model?.deck ?? null;
        selectedDialog.value = model?.dialog ?? null;

        form.group = model?.group || 'main';
        form.unit_id = selectedUnit.value?.id || model?.unit_id || null;
        form.title = model?.title || '';
        form.description = model?.description || '';
        form.document = model?.document || documentPreset.createDocument();
        form.deck_id = model?.deck?.id || model?.deck_id || null;
        form.dialog_id = model?.dialog?.id || model?.dialog_id || null;
        form.unlock_conditions = model?.unlock_conditions || [];
        form.published = !!model?.published;

        // ensureLessonSkillIds(form.document);

        defaults();
        clearErrors();
    };

    const editor = useResourceEditor({
        initialForm: {
            group: 'main',
            unit_id: null,
            title: '',
            description: '',
            document: documentPreset.createDocument(),
            deck_id: null,
            dialog_id: null,
            unlock_conditions: [],
            published: false,
        },
        getLoadIdentifier: () => lessonId.value,
        routeBase: 'lessons',
        fetchModel: lessonLoader.fetchLesson,
        resetModel: lessonLoader.setLesson,
        populateForm,
        getBlocks: (document) => document?.skills?.flatMap((skill) => skill.blocks ?? []) ?? [],
        extractSavedModel: (response) => response.data.lesson ?? response.data.data ?? null,
        beforeReload: () => {
            selectedUnit.value = initialUnit.value;
            selectedDeck.value = null;
            selectedDialog.value = null;
        },
        beforeSave: ({publish}, {form}) => {
            const previousPublished = form.published;

            form.published = !!publish;

            return () => {
                form.published = previousPublished;
            };
        },
        onSaveSuccess: () => {
            NotificationStore.addNotification('OK, the Lesson was successfully saved.', 'success');
        },
        onSaveError: () => {
            NotificationStore.addNotification('Oops — the Lesson could not be saved.', 'error');
        },
        onDeleteSuccess: () => {
            NotificationStore.addNotification('OK, the Lesson was successfully deleted.', 'success');
            window.location.href = route('lesson-planner.index');
        },
        onDeleteError: () => {
            NotificationStore.addNotification('Oops — the Lesson could not be deleted.', 'error');
        },
    });

    return {
        form: editor.form,
        errors: editor.errors,
        isDirty: editor.isDirty,
        processing: editor.processing,
        recentlySuccessful: editor.recentlySuccessful,
        reset: editor.reset,
        isSaving: editor.isSaving,
        isDeleting: editor.isDeleting,
        isLoadingForm: editor.isLoadingForm,
        loadForm: editor.loadForm,
        reloadForm: editor.reloadForm,
        saveLesson: editor.saveResource,
        deleteLesson: editor.deleteResource,
        sentenceModels: editor.documentLoader.sentenceModels,
        lesson: lessonLoader.lesson,
        lessonNotFound: lessonLoader.lessonNotFound,
        isLoadingLesson: lessonLoader.isLoadingLesson,
        allowedBlockTypes: documentPreset.allowedBlockTypes,
        isUnitLocked,
        selectedUnit,
        selectedDeck,
        selectedDialog,
        setSelectedUnit,
        setSelectedDeck,
        setSelectedDialog,
    };
}
