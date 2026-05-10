import {ref} from "vue";
import {route} from "ziggy-js";
import {usePageLoader} from "./usePageLoader.js";
import {getDocumentPreset} from "../../components/Blocks/documentPresets.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";

export function usePageEditor({
                                  pageId = null,
                              } = {}) {
    const NotificationStore = useNotificationStore();
    const documentPreset = getDocumentPreset('wiki');
    const pageLoader = usePageLoader();

    const selectedParent = ref(null);

    const setSelectedParent = (page = null) => {
        selectedParent.value = page;
    };

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        pageLoader.setPage(model);

        selectedParent.value = model?.parent ?? null;

        form.slug = model?.slug || 'page';
        form.title = model?.title || 'Page';
        form.summary = model?.summary || '';
        form.document = model?.document || documentPreset.createDocument();
        form.status = model?.status || 'draft';
        form.locale = model?.locale || 'en';
        form.sort_order = model?.sort_order || 0;
        form.parent_id = selectedParent.value?.id || model?.parent_id || null;

        defaults();
        clearErrors();
    };

    const editor = useResourceEditor({
        initialForm: {
            slug: '',
            title: '',
            summary: '',
            document: documentPreset.createDocument(),
            status: 'draft',
            locale: 'en',
            sort_order: 0,
            parent_id: null,
        },
        getLoadIdentifier: () => pageId.value,
        routeBase: 'wiki',
        fetchModel: pageLoader.fetchPage,
        resetModel: pageLoader.setPage,
        populateForm,
        getBlocks: (document) => document?.blocks ?? [],
        extractSavedModel: (response) => response.data.page ?? response.data.data ?? null,
        beforeReload: () => {
            selectedParent.value = null;
        },
        beforeSave: ({publish}, {form}) => {
            const previousStatus = form.status;

            form.status = publish ? 'published' : 'draft';

            return () => {
                form.status = previousStatus;
            };
        },
        onSaveSuccess: () => {
            NotificationStore.addNotification('OK, the Page was successfully saved.', 'success');
        },
        onSaveError: () => {
            NotificationStore.addNotification('Oops — the Page could not be saved.', 'error');
        },
        onDeleteSuccess: () => {
            NotificationStore.addNotification('OK, the Page was successfully deleted.', 'success');
            window.location.href = route('wiki.show');
        },
        onDeleteError: () => {
            NotificationStore.addNotification('Oops — the Page could not be deleted.', 'error');
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
        savePage: editor.saveResource,
        deletePage: editor.deleteResource,
        sentenceModels: editor.documentLoader.sentenceModels,
        page: pageLoader.page,
        pageNotFound: pageLoader.pageNotFound,
        isLoadingPage: pageLoader.isLoadingPage,
        descendantIds: pageLoader.descendantIds,
        allowedBlockTypes: documentPreset.allowedBlockTypes,
        selectedParent,
        setSelectedParent,
    };
}
