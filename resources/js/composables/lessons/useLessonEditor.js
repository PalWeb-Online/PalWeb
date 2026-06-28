import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {useLessonLoader} from "./useLessonLoader.js";
import {getDocumentPreset} from "../../components/Blocks/documentPresets.js";
import {useDocumentResourceEditor} from "../documents/useDocumentResourceEditor.js";
import {router} from "@inertiajs/vue3";

export function useLessonEditor({
                                    lessonId = null,
                                    initialUnit = null,
                                } = {}) {
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

        selectedUnit.value = model?.unit ?? initialUnit.value ?? null;
        selectedDeck.value = model?.deck ?? null;
        selectedDialog.value = model?.dialog ?? null;

        form.group = model?.group ?? 'main';
        form.unit_id = selectedUnit.value?.id ?? model?.unit_id ?? null;
        form.title = model?.title ?? '';
        form.description = model?.description ?? '';
        form.document = model?.document ?? documentPreset.createDocument();
        form.deck_id = model?.deck?.id ?? model?.deck_id ?? null;
        form.dialog_id = model?.dialog?.id ?? model?.dialog_id ?? null;
        form.unlock_conditions = model?.unlock_conditions ?? [];
        form.published = model?.published ?? false;

        // ensureLessonSkillIds(form.document);

        defaults();
        clearErrors();
    };

    const redirectToEditRoute = (lesson = null) => {
        if (lessonId.value || !lesson?.id) return;

        router.visit(route('lesson-planner.lesson', lesson.id), {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    };

    const editor = useDocumentResourceEditor({
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
        populateForm,
        extractSavedModel: (response) => response.data.lesson ?? response.data.data ?? null,
        getLoadIdentifier: () => lessonId.value,
        fetchModel: lessonLoader.fetchLesson,
        resetModel: lessonLoader.setLesson,
        label: 'Lesson',
        routeBase: 'lessons',
        getBlocks: (document) => document?.skills?.flatMap((skill) => skill.blocks ?? []) ?? [],
        beforeReload: () => {
            selectedUnit.value = initialUnit.value;
            selectedDeck.value = null;
            selectedDialog.value = null;
        },
        beforeSave: ({publish}, {form}) => {
            const previousPublished = form.published;

            if (publish !== undefined) {
                form.published = !!publish;
            }

            return () => {
                form.published = previousPublished;
            };
        },
        afterSave: (response, savedModel) => {
            redirectToEditRoute(savedModel);
        },
        onDeleteSuccess: () => {
            router.get(route('lesson-planner.index'));
        },
    });

    return {
        form: editor.form,
        errors: editor.errors,
        isDirty: editor.isDirty,
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
