import {route} from "ziggy-js";
import {ref} from "vue";

export function useResourceActions({
                                       routeBase,
                                       label = 'Model',
                                       getIdentifier = (model) => model?.id,
                                       getDestroyUrl = null,
                                       beforeDelete = null,
                                       afterDelete = null,
                                       onDeleteSuccess = null,
                                       onDeleteError = null,
                                   } = {}) {
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

        if (!confirm(`Are you sure you want to delete this ${label}?`)) {
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

                await onDeleteSuccess?.(response, model, identifier, options);
                await options.onSuccess?.(response, model, identifier, options);

                return response;

            } catch (error) {
                await onDeleteError?.(error, model, identifier, options);
                await options.onError?.(error, model, identifier, options);

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
