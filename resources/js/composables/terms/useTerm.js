import {onMounted, reactive, ref, watch} from "vue";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

export function useTerm(props = {}) {
    const term = reactive({});
    const isLoading = ref(true);

    const {
        isDeleting: isDeletingTerm,
        deleteResource: deleteTerm,
    } = useResourceDelete({
        routeBase: 'terms',
        label: 'Term',
        onDeleteSuccess: () => {
            router.get(route('terms.index'));
        },
    });

    onMounted(() => {
        if (!props?.model) {
            isLoading.value = false;
            return;
        }

        Object.assign(term, props.model);
        isLoading.value = false;
    });

    watch(() => props?.model,
        (newTerm) => {
            if (!newTerm) return;

            Object.assign(term, newTerm);
        },
        {deep: true}
    );

    return {
        term,
        isLoading,
        isDeletingTerm,
        deleteTerm
    };
}
