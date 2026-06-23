import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

export function useResourceActions({
                                       routeBase,
                                       label = 'model',
                                       getIdentifier = (model) => model?.id,
                                       getDestroyRouteName = () => `${routeBase}.destroy`,
                                       getDestroyUrl = null,
                                   }) {
    const resolveDestroyUrl = (model, options = {}) => {
        const identifier = getIdentifier(model, options);

        return getDestroyUrl?.(model, identifier, options)
            ?? route(getDestroyRouteName(model, options), identifier);
    };

    const deleteResource = (model, options = {}) => {
        if (!getIdentifier(model, options)) return;
        if (!confirm(`Are you sure you want to delete this ${label}?`)) return;

        router.delete(resolveDestroyUrl(model, options), options);
    };

    return {
        deleteResource
    };
}
