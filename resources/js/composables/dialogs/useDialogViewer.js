import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useDialogLoader} from "./useDialogLoader.js";

export function useDialogViewer() {
    const dialogLoader = useDialogLoader();

    const {
        load: loadDialog,
        reload: reloadDialog,
    } = useResourceViewer({
        fetchModel: (dialogId, options = {}) => dialogLoader.fetchDialog(dialogId, {
            include: 'show',
            ...options,
        }),
        resetModel: dialogLoader.setDialog,
    });

    return {
        ...dialogLoader,
        loadDialog,
        reloadDialog,
    };
}
