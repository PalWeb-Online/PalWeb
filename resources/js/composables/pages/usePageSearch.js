import {unref} from "vue";
import {useResourceSearch} from "../resources/useResourceSearch.js";

export function usePageSearch({
                                  currentPageId = null
                              } = {}) {
    const {
        searchResource: searchPages,
    } = useResourceSearch({
        routeName: 'api.wiki.search',
        params: () => ({
            page_id: unref(currentPageId) ?? null,
        }),
    });

    return {
        searchPages,
    };
}
