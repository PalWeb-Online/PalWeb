import {onMounted, reactive, ref, watch} from "vue";
import {useResourceActions} from "../resources/useResourceActions.js";

export function useTerm(props = {}) {
    const term = reactive({});
    const isLoading = ref(true);

    const {
        deleteResource: deleteTerm
    } = useResourceActions({
        routeBase: 'terms',
        label: 'Term',
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

    return {term, isLoading, deleteTerm};
}
