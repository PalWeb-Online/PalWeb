import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useDialogLoader() {
    const {
        model: dialog,
        notFound: dialogNotFound,
        isLoading: isLoadingDialog,
        setModel: setDialog,
        fetchShowModel: fetchShowDialog,
        fetchEditModel: fetchEditDialog,
    } = useResourceLoader({
        routeBase: 'dialogs',
        routeParam: 'dialog',
        extractModel: (response) => response.data.dialog ?? null,
    });

    return {
        dialog,
        dialogNotFound,
        isLoadingDialog,
        setDialog,
        fetchShowDialog,
        fetchEditDialog,
    };
}
