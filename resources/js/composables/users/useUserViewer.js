import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useUserLoader} from "./useUserLoader.js";

export function useUserViewer() {
    const userLoader = useUserLoader();

    const {
        load: loadUser,
        reload: reloadUser,
    } = useResourceViewer({
        fetchModel: (userUsername, options = {}) => userLoader.fetchShowUser(userUsername, {
            ...options,
        }),
        resetModel: () => userLoader.setUser,
    });

    return {
        ...userLoader,
        loadUser,
        reloadUser,
    };
}
