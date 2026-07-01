import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useDialogLoader() {
    const {
        model: dialog,
        notFound: dialogNotFound,
        isLoading: isLoadingDialog,
        setModel: setDialog,
        fetchModel: fetchDialog,
    } = useResourceLoader({
        getUrl: (dialogId, options = {}) => route(options.routeName ?? 'api.dialogs.fetch', {
            dialog: dialogId,
            ...(options.params ?? {}),
        }),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.dialog ?? null,
    });

    return {
        dialog,
        dialogNotFound,
        isLoadingDialog,
        setDialog,
        fetchDialog,
    };
}
