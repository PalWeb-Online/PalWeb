import {computed, onMounted, ref} from 'vue';
import {usePaginator} from '../usePaginator.js';
import {apiUrl} from '../../utils/apiUrl.js';

const emptyCollection = () => ({
    data: [],
    meta: {
        links: [],
        current_page: 1,
        last_page: 1,
        total: 0,
    },
});

const queryParams = () => Object.fromEntries(new URLSearchParams(window.location.search));

const hasValue = (value) => value !== null && value !== undefined && value !== '' && value !== false;

export function useResourceIndex({
                                     routeBase,
                                     url = apiUrl(`${routeBase}`),
                                     resourceKey = routeBase,
                                     defaultFilters = {},
                                     toggleFilters = [],
                                     onResponse = null,
                                 }) {
    const collection = ref(emptyCollection());
    const totalCount = ref(0);
    const filters = ref({ ...defaultFilters });
    const loading = ref(true);
    const error = ref(null);

    async function fetchIndex(params = {}, options = {}) {
        const paginationOnly = options.paginationOnly ?? false;

        if (!paginationOnly) {
            loading.value = true;
        }

        error.value = null;

        try {
            const { data } = await axios.get(url, { params });
            const resource = data[resourceKey] ?? emptyCollection();

            collection.value = resource;

            updatePagination(resource.meta ?? emptyCollection().meta);

            if (!paginationOnly) {
                totalCount.value = data.totalCount ?? resource.meta?.total ?? 0;
                filters.value = {
                    ...defaultFilters,
                    ...(data.filters ?? {}),
                };
                onResponse?.(data);
            }

            return data;

        } catch (caughtError) {
            error.value = caughtError;
            console.error(`Failed to fetch ${resourceKey}:`, caughtError);
            return null;

        } finally {
            if (!paginationOnly) {
                loading.value = false;
            }
        }
    }

    const fetchPaginationPage = async (params = {}) => {
        isLoadingPaginator.value = true;

        try {
            await fetchIndex(params, { paginationOnly: true });
        } finally {
            isLoadingPaginator.value = false;
        }
    };

    const {
        currentPage,
        lastPage,
        pageNumbers,
        goToPage,
        updatePagination,
        isLoadingPaginator,
    } = usePaginator(fetchPaginationPage);

    onMounted(() => {
        const params = queryParams();

        filters.value = {
            ...filters.value,
            ...params,
        };
        currentPage.value = parseInt(params.page) || 1;

        fetchIndex(params);
    });

    function updateFilter({
                              filter,
                              value,
                          }) {
        const searchParams = new URLSearchParams(window.location.search);
        const currentValue = searchParams.get(filter) ?? filters.value[filter] ?? '';
        const nextValue = toggleFilters.includes(filter) && String(currentValue) === String(value) ? '' : value;

        if (hasValue(nextValue)) {
            searchParams.set(filter, nextValue);
        } else {
            searchParams.delete(filter);
        }

        searchParams.delete('page');
        currentPage.value = 1;

        const params = Object.fromEntries(searchParams.entries());
        const queryString = searchParams.toString();

        window.history.pushState({}, '', queryString ? `?${queryString}` : window.location.pathname);

        fetchIndex(params);
    }

    const hasResults = computed(() => totalCount.value > 0);

    return {
        collection,
        totalCount,
        filters,
        loading,
        error,
        hasResults,
        currentPage,
        lastPage,
        pageNumbers,
        isLoadingPaginator,
        goToPage,
        updateFilter,
    };
}
