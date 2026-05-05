import axios from "axios";
import {route} from "ziggy-js";

export function useDialogSearch({
                                    currentLessonId = null,
                                } = {}) {
    const searchDialogs = async (q = '') => {
        const response = await axios.get(route('api.dialogs.search'), {
            params: {
                q,
                lesson_id: currentLessonId?.value ?? null,
            },
        });

        return response.data.data ?? [];
    };

    return {
        searchDialogs,
    };
}
