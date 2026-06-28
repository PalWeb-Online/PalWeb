import {ref} from "vue";
import {route} from "ziggy-js";
import {usePageLoader} from "./usePageLoader.js";
import {getDocumentPreset} from "../../components/Blocks/documentPresets.js";
import {useDocumentResourceEditor} from "../documents/useDocumentResourceEditor.js";
import {router} from "@inertiajs/vue3";

export function usePageEditor({
                                  pageId = null,
                              } = {}) {
    const documentPreset = getDocumentPreset('wiki');
    const pageLoader = usePageLoader();

    const selectedParent = ref(null);

    const setSelectedParent = (page = null) => {
        selectedParent.value = page;
    };

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        pageLoader.setPage(model);

        selectedParent.value = model?.parent ?? null;

        form.slug = model?.slug ?? '';
        form.title = model?.title ?? '';
        form.summary = model?.summary ?? '';
        form.document = model?.document ?? documentPreset.createDocument();
        form.status = model?.status ?? 'draft';
        form.locale = model?.locale ?? 'en';
        form.position = Math.max(1, Number(model?.position ?? 1));
        form.parent_id = selectedParent.value?.id ?? model?.parent_id ?? null;

        defaults();
        clearErrors();
    };

    const redirectToEditRoute = (page = null) => {
        if (pageId.value || !page?.id) return;

        router.visit(route('wiki.edit', page.id), {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    };

    const editor = useDocumentResourceEditor({
        initialForm: {
            slug: '',
            title: '',
            summary: '',
            document: documentPreset.createDocument(),
            status: 'draft',
            locale: 'en',
            position: 1,
            parent_id: null,
        },
        populateForm,
        extractSavedModel: (response) => response.data.page ?? response.data.data ?? null,
        getLoadIdentifier: () => pageId.value,
        fetchModel: pageLoader.fetchPage,
        resetModel: pageLoader.setPage,
        label: 'Page',
        routeBase: 'wiki',
        getBlocks: (document) => document?.blocks ?? [],
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
        afterSave: async (response, savedModel) => {
            await pageLoader.fetchWikiTree();
            redirectToEditRoute(savedModel);
        },
        onDeleteSuccess: () => {
            router.get(route('wiki.index'));
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
        savePage: editor.saveResource,
        deletePage: editor.deleteResource,
        sentenceModels: editor.documentLoader.sentenceModels,
        page: pageLoader.page,
        pageNotFound: pageLoader.pageNotFound,
        isLoadingPage: pageLoader.isLoadingPage,
        pageTree: pageLoader.pageTree,
        isLoadingTree: pageLoader.isLoadingTree,
        fetchWikiTree: pageLoader.fetchWikiTree,
        descendantIds: pageLoader.descendantIds,
        allowedBlockTypes: documentPreset.allowedBlockTypes,
        selectedParent,
        setSelectedParent,
    };
}
