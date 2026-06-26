import {useResourceDelete} from "../resources/useResourceDelete.js";

export function useActivity() {
    const {
        isDeleting: isDeletingActivity,
        deleteResource: deleteActivity,
    } = useResourceDelete({
        routeBase: 'activities',
        label: 'Activity',
    });

    return {
        isDeletingActivity,
        deleteActivity,
    };
}
