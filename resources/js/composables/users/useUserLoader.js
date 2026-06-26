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
        getUrl: (userId, options = {}) => route(options.routeName ?? 'api.users.fetch', {
            user: userId,
            ...(options.params ?? {}),
        }),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
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
