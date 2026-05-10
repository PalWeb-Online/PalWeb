import {useForm} from "../useForm.js";
import {useValidationErrors} from "../useValidationErrors.js";
import {ref} from "vue";
import {useDocumentResourceLoader} from "./useDocumentResourceLoader.js";

export function useDocumentResourceEditor({
                                      initialForm,
                                      populateForm,
                                      extractSavedModel,
                                      getLoadIdentifier,
                                      getSaveIdentifier = getLoadIdentifier,
                                      getDeleteIdentifier = getSaveIdentifier,
                                      fetchModel,
                                      resetModel,
                                      getBlocks,
                                      routeBase = null,
                                      getStoreUrl = null,
                                      getUpdateUrl = null,
                                      getDeleteUrl = null,
                                      beforeLoad = null,
                                      afterLoad = null,
                                      beforeReload = null,
                                      beforeSave = null,
                                      beforeDelete = null,
                                      onSaveSuccess = null,
                                      onSaveError = null,
                                      onDeleteSuccess = null,
                                      onDeleteError = null,
                                  }) {
    const documentLoader = useDocumentResourceLoader();

    const isSaving = ref(false);
    const isDeleting = ref(false);
    const isLoadingForm = ref(false);

    const withSaving = async (callback) => {
        if (isSaving.value) return null;

        isSaving.value = true;

        try {
            return await callback();
        } finally {
            isSaving.value = false;
        }
    };

    const withDeleting = async (callback) => {
        isDeleting.value = true;

        try {
            return await callback();
        } finally {
            isDeleting.value = false;
        }
    };

    const withLoadingForm = async (callback) => {
        isLoadingForm.value = true;

        try {
            return await callback();
        } finally {
            isLoadingForm.value = false;
        }
    };

    const resolveStoreUrl = (options = {}) => {
        return getStoreUrl?.(options) ?? route(`${routeBase}.store`);
    };

    const resolveUpdateUrl = (identifier, options = {}) => {
        return getUpdateUrl?.(identifier, options) ?? route(`${routeBase}.update`, identifier);
    };

    const resolveDeleteUrl = (identifier) => {
        return getDeleteUrl?.(identifier) ?? route(`${routeBase}.destroy`, identifier);
    };

    const {
        form,
        errors,
        processing,
        recentlySuccessful,
        isDirty,
        payload,
        defaults,
        reset,
        setErrors,
        clearErrors,
        setRecentlySuccessful,
    } = useForm(initialForm);

    const {
        handleValidationError,
    } = useValidationErrors({
        setErrors,
    });

    const hasIdentifier = (identifier) => {
        return identifier !== null && identifier !== undefined && identifier !== '';
    };

    const loadForm = async () => {
        await withLoadingForm(async () => {
            beforeLoad?.();

            const identifier = getLoadIdentifier();
            const model = hasIdentifier(identifier)
                ? await fetchModel(identifier)
                : null;

            populateForm(model, {
                form, defaults, clearErrors,
            });

            afterLoad?.(model);
        });

        const blocks = getBlocks(form.document) ?? [];

        await documentLoader.loadSentenceModelsForBlocks(blocks);
    };

    const reloadForm = async () => {
        resetModel();
        documentLoader.resetDocuments();

        beforeReload?.();

        await loadForm();
    };

    const saveResource = async (options = {}) => {
        return await withSaving(async () => {
            processing.value = true;
            clearErrors();

            const rollback = beforeSave?.(options, {
                form,
            });

            try {
                const identifier = getSaveIdentifier();
                const isUpdate = hasIdentifier(identifier);

                const response = isUpdate
                    ? await axios.patch(resolveUpdateUrl(identifier, options), payload())
                    : await axios.post(resolveStoreUrl(options), payload());

                const savedModel = extractSavedModel(response);

                if (savedModel) {
                    populateForm(savedModel, {
                        form, defaults, clearErrors,
                    });
                } else {
                    defaults();
                }

                setRecentlySuccessful();
                onSaveSuccess?.(response, savedModel, options)

                return response;

            } catch (error) {
                rollback?.();

                if (!handleValidationError(error)) {
                    onSaveError?.(error);
                }

                return null;

            } finally {
                processing.value = false;
            }
        });
    };

    const deleteResource = async () => {
        const identifier = getDeleteIdentifier();

        if (!hasIdentifier(identifier)) return null;
        if (!confirm('Are you sure you want to delete this model?')) return null;

        return await withDeleting(async () => {
            beforeDelete?.(identifier);

            try {
                const response = await axios.delete(resolveDeleteUrl(identifier));

                resetModel();
                documentLoader.resetDocuments();

                onDeleteSuccess?.(response);

                return response;

            } catch (error) {
                onDeleteError?.(error);

                return null;
            }
        });
    };

    return {
        documentLoader,
        form,
        errors,
        isDirty,
        processing,
        recentlySuccessful,
        reset,
        defaults,
        clearErrors,
        isSaving,
        isDeleting,
        isLoadingForm,
        loadForm,
        reloadForm,
        saveResource,
        deleteResource,
    };
}
