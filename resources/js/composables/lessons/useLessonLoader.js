import {ref} from "vue";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useLessonLoader() {
    const unit = ref(null);

    const {
        model: lesson,
        notFound: lessonNotFound,
        isLoading: isLoadingLesson,
        setModel: setLesson,
        fetchShowModel: fetchShowLesson,
        fetchEditModel: fetchEditLesson,
    } = useResourceLoader({
        routeBase: 'lessons',
        routeParam: 'lesson',
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
        fetchShowLesson,
        fetchEditLesson,
    };
}
