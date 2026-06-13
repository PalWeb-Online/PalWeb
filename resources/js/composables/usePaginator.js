import { ref, computed } from "vue";

export function usePaginator(fetchFunction) {
    const currentPage = ref(1);
    const lastPage    = ref(1);

    const pageNumbers = computed(() => {
        const pages = [];
        const total = lastPage.value;
        const current = currentPage.value;

        if (total <= 7) {
            for (let i = 1; i <= total; i++) pages.push(i);
        } else {
            pages.push(1);
            if (current > 3) pages.push('...');
            for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
                pages.push(i);
            }
            if (current < total - 2) pages.push('...');
            pages.push(total);
        }
        return pages;
    });

    function goToPage(page) {
        if (page === '...' || page < 1 || page > lastPage.value || page === currentPage.value) return;
        currentPage.value = page;
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.set('page', page);
        window.history.pushState({}, '', '?' + searchParams.toString());
        fetchFunction(Object.fromEntries(searchParams.entries()));
        window.scrollTo(0, 0);
    }

    function updatePagination(meta) {
        currentPage.value = meta.current_page;
        lastPage.value    = meta.last_page;
    }

    return { currentPage, lastPage, pageNumbers, goToPage, updatePagination };
}