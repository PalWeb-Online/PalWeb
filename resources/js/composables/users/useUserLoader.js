import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useUserLoader() {

    const {
        model: user,
        notFound: userNotFound,
        isLoading: isLoadingUser,
        setModel: setUser,
        fetchModel: fetchUser,
    } = useResourceLoader({
        getUrl: (username) => route('api.users.fetch', username),
        extractModel: (response) => response.data.user ?? null,
    });

    return {
        user,
        userNotFound,
        isLoadingUser,
        setUser,
        fetchUser,
    };
}
