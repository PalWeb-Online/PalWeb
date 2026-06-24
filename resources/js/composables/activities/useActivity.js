import {useResourceActions} from "../resources/useResourceActions.js";

export function useActivity() {
    const {
        isDeleting: isDeletingActivity,
        deleteResource: deleteActivity,
    } = useResourceActions({
        routeBase: 'activities',
        label: 'Activity',
    });

    return {
        isDeletingActivity,
        deleteActivity,
    };
}
