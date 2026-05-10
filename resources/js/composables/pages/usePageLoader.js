import {ref} from "vue";
import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function usePageLoader() {
    const descendantIds = ref([]);
    const pageTree = ref([]);
    const isLoadingTree = ref(false);

    const {
        model: page,
        notFound: pageNotFound,
        isLoading: isLoadingPage,
        setModel: setPage,
        fetchModel: fetchPage,
    } = useResourceLoader({
        getUrl: (pageId) => route('api.wiki.fetch', pageId),
        extractModel: (response) => response.data.page ?? null,
        extractMeta: (response) => ({
            descendantIds: response.data.descendant_ids ?? [],
        }),
        onSetModel: (meta = {}) => {
            descendantIds.value = meta.descendantIds ?? [];
        },
    });

    const fetchWikiTree = async () => {
        isLoadingTree.value = true;

        try {
            const response = await axios.get(route('api.wiki.tree'));
            pageTree.value = response.data.page_tree ?? [];

        } finally {
            isLoadingTree.value = false;
        }
    };

    return {
        page,
        pageNotFound,
        isLoadingPage,
        descendantIds,
        pageTree,
        isLoadingTree,
        setPage,
        fetchPage,
        fetchWikiTree,
    };
}
