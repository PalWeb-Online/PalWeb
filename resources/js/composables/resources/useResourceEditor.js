import {ref, watch} from "vue";
import {route} from "ziggy-js";
import {useForm} from "../useForm.js";
import {useResourceDelete} from "./useResourceDelete.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";

export function useResourceEditor({
                                      initialForm,
                                      populateForm,
                                      extractSavedModel,
                                      extractSavedIdentifier = (model) => model?.id ?? null,
                                      getLoadIdentifier,
                                      getSaveIdentifier = getLoadIdentifier,
                                      getDeleteIdentifier = getSaveIdentifier,
                                      fetchModel,
                                      resetModel,
                                      label = 'Model',
                                      routeBase = null,
                                      getStoreUrl = null,
                                      getUpdateUrl = null,
                                      getDeleteUrl = null,
                                      beforeLoad = null,
                                      afterLoad = null,
                                      beforeReload = null,
                                      afterReload = null,
                                      beforeSave = null,
                                      afterSave = null,
                                      beforeDelete = null,
                                      afterDelete = null,
                                      onSaveSuccess = null,
                                      onSaveError = null,
                                      onDeleteSuccess = null,
                                      onDeleteError = null,
                                  }) {
    const NotificationStore = useNotificationStore();

    const isSaving = ref(false);
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

    const {
        form,
        errors,
        recentlySuccessful,
        isDirty,
        payload,
        defaults,
        reset,
        setErrors,
        clearErrors,
        setRecentlySuccessful,
    } = useForm(initialForm);

    const hasIdentifier = (identifier) => {
        return identifier !== null && identifier !== undefined && identifier !== '';
    };

    const savedIdentifier = ref(null);

    watch(
        () => getSaveIdentifier(),
        (identifier) => {
            savedIdentifier.value = hasIdentifier(identifier) ? identifier : null;
        },
        {immediate: true}
    );

    const resolveSaveIdentifier = () => {
        const identifier = getSaveIdentifier();

        return hasIdentifier(identifier) ? identifier : savedIdentifier.value;
    };

    const loadForm = async () => {
        let loadedModel = null;

        await withLoadingForm(async () => {
            beforeLoad?.();

            const identifier = getLoadIdentifier();

            loadedModel = hasIdentifier(identifier)
                ? await fetchModel(identifier)
                : null;

            populateForm(loadedModel, {
                form, defaults, clearErrors,
            });

            await afterLoad?.(loadedModel, {
                form, defaults, clearErrors,
            });
        });

        return loadedModel;
    };

    const reloadForm = async () => {
        resetModel();

        await beforeReload?.();

        const model = await loadForm();

        await afterReload?.(model, {
            form,
            defaults,
            clearErrors,
        });

        return model;
    };

    const saveResource = async (options = {}) => {
        return await withSaving(async () => {
            clearErrors();

            const rollback = beforeSave?.(options, {
                form,
            });

            try {
                const identifier = resolveSaveIdentifier();
                const isUpdate = hasIdentifier(identifier);

                const response = isUpdate
                    ? await axios.patch(resolveUpdateUrl(identifier, options), payload())
                    : await axios.post(resolveStoreUrl(options), payload());

                const savedModel = extractSavedModel(response);
                const identifierFromSavedModel = extractSavedIdentifier(savedModel, response, options);

                if (hasIdentifier(identifierFromSavedModel)) {
                    savedIdentifier.value = identifierFromSavedModel;
                }

                if (savedModel) {
                    populateForm(savedModel, {
                        form,
                        defaults,
                        clearErrors,
                    });
                } else {
                    defaults();
                }

                await afterSave?.(response, savedModel, options, {
                    form,
                    defaults,
                    clearErrors,
                });

                setRecentlySuccessful();

                onSaveSuccess?.(response, savedModel, options);
                NotificationStore.addNotification(`${label} was successfully saved.`, 'success');

                return response;

            } catch (error) {
                rollback?.();

                if (error?.response?.status === 422) {
                    setErrors?.(error.response.data.errors ?? {});
                }

                onSaveError?.(error);
                NotificationStore.addNotification(`Oops — ${label} could not be saved.`, 'error');

                return null;
            }
        });
    };

    const actions = useResourceDelete({
        routeBase,
        label,
        getIdentifier: () => {
            const identifier = getDeleteIdentifier();

            return hasIdentifier(identifier)
                ? identifier
                : savedIdentifier.value;
        },
        getDestroyUrl: (_, identifier, options) => {
            return getDeleteUrl?.(identifier, options);
        },
        beforeDelete,
        afterDelete: async (response, model, identifier, options) => {
            resetModel();
            savedIdentifier.value = null;

            await afterDelete?.(response, model, identifier, options, {
                form,
                defaults,
                clearErrors,
            });
        },
        onDeleteSuccess,
        onDeleteError,
    });

    return {
        form,
        errors,
        isDirty,
        recentlySuccessful,
        reset,
        defaults,
        clearErrors,
        isSaving,
        isDeleting: actions.isDeleting,
        isLoadingForm,
        loadForm,
        reloadForm,
        saveResource,
        deleteResource: actions.deleteResource,
    };
}
