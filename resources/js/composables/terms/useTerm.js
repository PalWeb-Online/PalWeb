import {onMounted, reactive, ref, watch} from "vue";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useRouter} from "vue-router";

export function useTerm(props = {}) {
    const router = useRouter();
    const term = reactive({});
    const isLoading = ref(true);

    const {
        isDeleting: isDeletingTerm,
        deleteResource: deleteTerm,
    } = useResourceDelete({
        label: 'term',
        routeBase: 'terms',
        onDeleteSuccess: () => {
            router.push({name: 'terms.index'});
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
