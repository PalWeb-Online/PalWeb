import {route} from "ziggy-js";
import {useUnitLoader} from "./useUnitLoader.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {router} from "@inertiajs/vue3";

export function useUnitEditor({
                                  unitId = null,
                              } = {}) {
    const unitLoader = useUnitLoader();

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        unitLoader.setUnit(model);

        form.position = model?.position ?? 0;
        form.title = model?.title ?? '';
        form.lessons = model?.lessons ?? [];
        form.published = model?.published ?? false;

        defaults();
        clearErrors();
    };

    const editor = useResourceEditor({
        initialForm: {
            position: 0,
            title: '',
            lessons: [],
            published: false,
        },
        populateForm,
        extractSavedModel: (response) => response.data.unit ?? response.data.data ?? null,
        getLoadIdentifier: () => unitId.value,
        fetchModel: unitLoader.fetchUnit,
        resetModel: unitLoader.setUnit,
        label: 'Unit',
        routeBase: 'units',
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
            router.get(route('lesson-planner.index'));
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
        saveUnit: editor.saveResource,
        deleteUnit: editor.deleteResource,
        unit: unitLoader.unit,
        unitNotFound: unitLoader.unitNotFound,
        isLoadingUnit: unitLoader.isLoadingUnit,
        descendantIds: unitLoader.descendantIds,
    };
}
