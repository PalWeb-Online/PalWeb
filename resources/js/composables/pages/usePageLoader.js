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
        getUrl: (pageIdentifier, options = {}) => route(options.routeName, {
            page: pageIdentifier,
            ...(options.params ?? {}),
        }),
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

    const fetchShowPage = (pageSlug, options = {}) => fetchPage(pageSlug, {
        ...options,
        routeName: 'api.wiki.show',
    });

    const fetchEditPage = (pageId, options = {}) => fetchPage(pageId, {
        ...options,
        routeName: 'api.wiki.edit',
    });

    return {
        page,
        pageNotFound,
        isLoadingPage,
        descendantIds,
        pageTree,
        isLoadingTree,
        setPage,
        fetchPage,
        fetchShowPage,
        fetchEditPage,
        fetchWikiTree,
    };
}
