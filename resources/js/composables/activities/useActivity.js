import {useResourceActions} from "../resources/useResourceActions.js";

export function useActivity() {
    const {
        deleteResource: deleteActivity
    } = useResourceActions({
        routeBase: 'activities',
        label: 'Activity',
    });

    return {deleteActivity};
}
