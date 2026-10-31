import {useUnitLoader} from "./useUnitLoader.js";
import {useResourceViewer} from "../resources/useResourceViewer.js";

export function useUnitViewer() {
    const unitLoader = useUnitLoader();

    const {
        load: loadUnit,
        reload: reloadUnit,
    } = useResourceViewer({
        fetchModel: unitLoader.fetchShowUnit,
        resetModel: unitLoader.setUnit,
    });

    return {
        ...unitLoader,
        loadUnit,
        reloadUnit,
    };
}
