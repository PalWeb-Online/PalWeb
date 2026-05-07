import {ref} from "vue";
import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useLessonLoader() {
    const unit = ref(null);

    const {
        model: lesson,
        notFound: lessonNotFound,
        isLoading: isLoadingLesson,
        setModel: setLesson,
        fetchModel: fetchLesson,
    } = useResourceLoader({
        getUrl: (lessonId) => route('api.lessons.fetch', lessonId),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.lesson ?? null,
        extractMeta: (response) => ({
            unit: response.data.unit ?? null,
        }),
        onSetModel: (meta = {}) => {
            unit.value = meta.unit ?? null;
        },
    });

    return {
        lesson,
        lessonNotFound,
        isLoadingLesson,
        unit,
        setLesson,
        fetchLesson,
    };
}
