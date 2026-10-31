import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useDialogLoader} from "./useDialogLoader.js";

export function useDialogViewer() {
    const dialogLoader = useDialogLoader();

    const {
        load: loadDialog,
        reload: reloadDialog,
    } = useResourceViewer({
        fetchModel: dialogLoader.fetchShowDialog,
        resetModel: dialogLoader.setDialog,
    });

    return {
        ...dialogLoader,
        loadDialog,
        reloadDialog,
    };
}
