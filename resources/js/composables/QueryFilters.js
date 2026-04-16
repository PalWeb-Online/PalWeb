import { watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useQueryFilters(filters, options = {}) {
    const {
        pageParam = "page",
        preserveState = true,
        preserveScroll = true,
        mode = "replace",
    } = options;

    let previousFilters = { ...filters.value };

    function updateFilter({ filter, value }) {
        const searchParams = new URLSearchParams(window.location.search);

        value ? searchParams.set(filter, value) : searchParams.delete(filter);
        searchParams.delete(pageParam);

        const params = Object.fromEntries(searchParams.entries());

        router.get(window.location.pathname, params, {
            preserveState,
            preserveScroll,
            replace: mode === "replace",
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
