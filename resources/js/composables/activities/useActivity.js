import {useResourceDelete} from "../resources/useResourceDelete.js";

export function useActivity() {
    const {
        isDeleting: isDeletingActivity,
        deleteResource: deleteActivity,
    } = useResourceDelete({
        label: 'activity',
        routeBase: 'activities',
    });

    return {
        isDeletingActivity,
        deleteActivity,
    };
}
