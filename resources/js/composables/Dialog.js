import {onMounted, reactive} from "vue";
import {route} from "ziggy-js";

export function useDialog(props) {
    const data = reactive({
        dialog: {},
        isLoading: true
    });

    const fetchDialog = async () => {
        if (props.model) {
            data.dialog = props.model;
            data.isLoading = false;

        } else {
            try {
                const response = await axios.get(route('dialogs.get', props.id));
                data.dialog = response.data.data;
                data.isLoading = false;

            } catch (error) {
                console.error("Error fetching Dialog:", error);
            }
        }
    }

    onMounted(fetchDialog);

    return {data};
}
