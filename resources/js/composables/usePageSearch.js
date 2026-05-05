import axios from "axios";
import {route} from "ziggy-js";

export function usePageSearch({
                                  currentPageId = null
                              } = {}) {
    const searchPages = async (q = '') => {
        const response = await axios.get(route('api.wiki.search'), {
            params: {
                q,
                page_id: currentPageId?.value ?? currentPageId ?? null,
            },
        });

        return response.data.data ?? [];
    };

    return {
        searchPages,
    };
}
