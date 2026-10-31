import { watch } from "vue";
import {useRoute, useRouter} from "vue-router";

export function useQueryFilters(filters, options = {}) {
    const router = useRouter();
    const currentRoute = useRoute();

    const {
        pageParam = "page",
        mode = "replace",
    } = options;

    let previousFilters = { ...filters.value };

    function updateFilter({ filter, value }) {
        const searchParams = new URLSearchParams(window.location.search);

        value ? searchParams.set(filter, value) : searchParams.delete(filter);
        searchParams.delete(pageParam);

        const params = Object.fromEntries(searchParams.entries());

        router[mode === "replace" ? "replace" : "push"]({
            path: currentRoute.path,
            query: params,
        });
    }

    watch(
        filters,
        (newFilters) => {
            for (const key in newFilters) {
                if (newFilters[key] !== previousFilters[key]) {
                    updateFilter({ filter: key, value: newFilters[key] });
                    previousFilters[key] = newFilters[key];
                }
            }
        },
        { deep: true }
    );

    return {
        updateFilter,
    };
}
