import {route} from "ziggy-js";
import {useActivityLoader} from "./useActivityLoader.js";
import {getDocumentPreset} from "../../components/Blocks/documentPresets.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useDocumentResourceEditor} from "../documents/useDocumentResourceEditor.js";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

export function useActivityEditor({
                                      activityId = null,
                                      initialLesson = null,
                                  } = {}) {
    const NotificationStore = useNotificationStore();
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
        fetchModel: activityLoader.fetchActivity,
        resetModel: activityLoader.setActivity,
        label: 'Activity',
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
        onSaveSuccess: () => {
            NotificationStore.addNotification('OK, the Activity was successfully saved.', 'success');
        },
        onSaveError: () => {
            NotificationStore.addNotification('Oops — the Activity could not be saved.', 'error');
        },
        onDeleteSuccess: () => {
            NotificationStore.addNotification('OK, the Activity was successfully deleted.', 'success');
            router.get(route('activity-planner.index'));
        },
        onDeleteError: () => {
            NotificationStore.addNotification('Oops — the Activity could not be deleted.', 'error');
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
        saveActivity: editor.saveResource,
        deleteActivity: editor.deleteResource,
        activity: activityLoader.activity,
        activityNotFound: activityLoader.activityNotFound,
        isLoadingActivity: activityLoader.isLoadingActivity,
        allowedBlockTypes: documentPreset.allowedBlockTypes,
    };
}
