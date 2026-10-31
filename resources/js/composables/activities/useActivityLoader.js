import {ref} from "vue";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useActivityLoader() {
    const lesson = ref(null);

    const {
        model: activity,
        notFound: activityNotFound,
        isLoading: isLoadingActivity,
        setModel: setActivity,
        fetchShowModel: fetchShowActivity,
        fetchEditModel: fetchEditActivity,
    } = useResourceLoader({
        routeBase: 'activities',
        routeParam: 'activity',
        extractModel: (response) => response.data.activity ?? null,
    });

    return {
        activity,
        activityNotFound,
        isLoadingActivity,
        lesson,
        setActivity,
        fetchShowActivity,
        fetchEditActivity,
    };
}
