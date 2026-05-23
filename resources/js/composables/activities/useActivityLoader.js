import {ref} from "vue";
import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useActivityLoader() {
    const lesson = ref(null);

    const {
        model: activity,
        notFound: activityNotFound,
        isLoading: isLoadingActivity,
        setModel: setActivity,
        fetchModel: fetchActivity,
    } = useResourceLoader({
        getUrl: (activityId) => route('api.activities.fetch', activityId),
        getRequestConfig: (options = {}) => ({
            params: {
                profile: options.profile ?? null,
                include: Array.isArray(options.include)
                    ? options.include.join(',')
                    : options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.activity ?? null,
    });

    return {
        activity,
        activityNotFound,
        isLoadingActivity,
        lesson,
        setActivity,
        fetchActivity,
    };
}
