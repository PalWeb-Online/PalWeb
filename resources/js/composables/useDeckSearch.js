import {route} from "ziggy-js";

export function useDeckSearch({
                                  currentLessonId = null,
                              } = {}) {
    const searchDecks = async (q = '') => {
        const response = await axios.get(route('api.decks.search'), {
            params: {
                q,
                lesson_id: currentLessonId?.value ?? null,
            },
        });

        return response.data.data ?? [];
    };

    return {
        searchDecks,
    };
}
