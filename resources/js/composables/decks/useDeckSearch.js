import {unref} from "vue";
import {useResourceSearch} from "../resources/useResourceSearch.js";

export function useDeckSearch({
                                  currentLessonId = null,
                              } = {}) {
    const {
        searchResource: searchDecks,
    } = useResourceSearch({
        routeName: 'api.decks.search',
        params: () => ({
            lesson_id: unref(currentLessonId) ?? null,
        }),
    });

    return {
        searchDecks,
    };
}
