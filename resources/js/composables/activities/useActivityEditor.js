import {useActivityLoader} from "./useActivityLoader.js";
import {getDocumentPreset} from "../../components/Blocks/documentPresets.js";
import {useDocumentResourceEditor} from "../documents/useDocumentResourceEditor.js";
import {ref} from "vue";
import {useRouter} from "vue-router";

export function useActivityEditor({
                                  activityId = null,
                                  initialLesson = null,
                              } = {}) {
    const router = useRouter();
    const documentPreset = getDocumentPreset('activity');
    const activityLoader = useActivityLoader();

    // never used outside the composable because it is not possible to reassign it
    const selectedLesson = ref(initialLesson.value);

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        activityLoader.setActivity(model);

        selectedLesson.value = model?.lesson ?? initialLesson.value ?? null;

        const defaultTitle = selectedLesson.value
            ? 'Activity ' + selectedLesson.value?.global_position + ': ' + selectedLesson.value?.title
            : 'Activity';

        form.lesson_id = selectedLesson.value?.id ?? null;
        form.title = model?.title ?? defaultTitle;
        form.document = model?.document ?? documentPreset.createDocument();
        form.published = model?.published ?? false;

        defaults();
        clearErrors();
    };

    const editor = useDocumentResourceEditor({
        initialForm: {
            lesson_id: null,
            title: '',
            document: documentPreset.createDocument(),
            published: false,
        },
        populateForm,
        extractSavedModel: (response) => response.data.activity ?? response.data.data ?? null,
        getLoadIdentifier: () => activityId.value,
        fetchModel: activityLoader.fetchEditActivity,
        resetModel: activityLoader.setActivity,
        label: 'activity',
        routeBase: 'activities',
        getBlocks: (document) => document?.blocks ?? [],
        beforeReload: () => {
            selectedLesson.value = initialLesson.value;
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
        onDeleteSuccess: () => {
            router.push({name: 'lesson-planner.index'});
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
        saveActivity: editor.saveResource,
        deleteActivity: editor.deleteResource,
        activity: activityLoader.activity,
        activityNotFound: activityLoader.activityNotFound,
        allowedBlockTypes: documentPreset.allowedBlockTypes,
    };
}
