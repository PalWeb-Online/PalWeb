import {unref} from "vue";
import {useResourceSearch} from "../resources/useResourceSearch.js";

export function useDialogSearch({
                                    currentLessonId = null,
                                } = {}) {
    const {
        searchResource: searchDialogs,
    } = useResourceSearch({
        routeName: 'api.dialogs.search',
        params: () => ({
            lesson_id: unref(currentLessonId) ?? null,
        }),
    });

    return {
        searchDialogs,
    };
}
