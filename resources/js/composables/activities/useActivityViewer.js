import {useActivityLoader} from "./useActivityLoader.js";
import {useDocumentResourceViewer} from "../documents/useDocumentResourceViewer.js";

export function useActivityViewer() {
    const activityLoader = useActivityLoader();

    const {
        load: loadActivity,
        reload: reloadActivity,
    } = useDocumentResourceViewer({
        fetchModel: (activityId) => activityLoader.fetchActivity(activityId, {
            include: [
                'scores'
            ],
        }),
        resetModel: activityLoader.setActivity,
        getBlocks: (document) => document?.blocks ?? [],
    });

    return {
        ...activityLoader,
        loadActivity,
        reloadActivity,
    };
}
