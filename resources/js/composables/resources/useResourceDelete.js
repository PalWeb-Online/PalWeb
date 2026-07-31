import {route} from "ziggy-js";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useI18n} from "vue-i18n";

export function useResourceDelete({
                                      routeBase,
                                      label = 'Model',
                                      getIdentifier = (model) => model?.id,
                                      getDestroyUrl = null,
                                      beforeDelete = null,
                                      afterDelete = null,
                                      onDeleteSuccess = null,
                                      onDeleteError = null,
                                  } = {}) {
    const {t} = useI18n();
    const NotificationStore = useNotificationStore();

    const isDeleting = ref(false);

    const hasIdentifier = (identifier) => {
        return identifier !== null && identifier !== undefined && identifier !== '';
    };

    const withDeleting = async (callback) => {
        if (isDeleting.value) return null;

        isDeleting.value = true;

        try {
            return await callback();
        } finally {
            isDeleting.value = false;
        }
    };

    const resolveDestroyUrl = (model, options = {}) => {
        const identifier = getIdentifier(model, options);

        return getDestroyUrl?.(model, identifier, options) ?? route(`${routeBase}.destroy`, identifier);
    };

    const deleteResource = async (model = null, options = {}) => {
        if (isDeleting.value) return null;

        const identifier = getIdentifier(model, options);

        if (!hasIdentifier(identifier)) return null;

        if (!confirm(t('forms.notifications.delete-confirm', {model: t(`actions.models.${label}`)}))) {
            return null;
        }

        return await withDeleting(async () => {
            await beforeDelete?.(model, identifier, options);
            await options.beforeDelete?.(model, identifier, options);

            try {
                const url = resolveDestroyUrl(model, options);

                const response = await axios.delete(url, options);

                await afterDelete?.(response, model, identifier, options);
                await options.afterDelete?.(response, model, identifier, options);

                if (onDeleteSuccess || options.onSuccess) {
                    await onDeleteSuccess?.(response, model, identifier, options);
                    await options.onSuccess?.(response, model, identifier, options);

                } else {
                    router.get(route('homepage'));
                }

                NotificationStore.addNotification(t('forms.notifications.delete-success', {model: t(`actions.models.${label}`)}), 'success');

                return response;

            } catch (error) {
                await onDeleteError?.(error, model, identifier, options);
                await options.onError?.(error, model, identifier, options);
                NotificationStore.addNotification(t('forms.notifications.delete-error', {model: t(`actions.models.${label}`)}), 'error');

                return null;
            }
        });
    };

    return {
        isDeleting,
        withDeleting,
        deleteResource,
        resolveDestroyUrl,
    };
}
