import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";

export function useDialog(props) {
    const dialog = reactive({});
    const isLoading = ref(true);

    const fetchDialog = async () => {
        if (props.model) {
            dialog.value = props.model;
            isLoading.value = false;

        } else {
            try {
                const response = await axios.get(route('dialogs.get', props.id));
                dialog.value = response.data.data;
                isLoading.value = false;

            } catch (error) {
                console.error("Error fetching Dialog:", error);
            }
        }
    }

    onMounted(fetchDialog);

    return {dialog, isLoading, fetchDialog};
}
