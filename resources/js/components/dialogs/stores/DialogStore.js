import {defineStore} from 'pinia';
import {reactive} from "vue";
import {route} from "ziggy-js";
import {cloneDeep} from "lodash";
import {useStateStore} from "./StateStore.js";

export const useDialogStore = defineStore('DialogStore', () => {
    const StateStore = useStateStore();

    const data = reactive({
        stagedDialog: {
            id: null,
            title: '',
            description: '',
            media: '',
            sentences: [],
        },
        originalDialog: null,
    });

    const saveDialog = async () => {
        try {
            if (!data.stagedDialog.id) {
                const response = await axios.post('/academy/dialogs', {
                    dialog: data.stagedDialog,
                });

                data.stagedDialog.id = response.data.dialog.id;
                data.stagedDialog.sentences.forEach(sentence => sentence.dialog_id = response.data.dialog.id);

            } else {
                await axios.patch('/academy/dialogs/' + data.stagedDialog.id, {
                    dialog: data.stagedDialog,
                });
            }

            data.originalDialog = cloneDeep(data.stagedDialog);

            StateStore.data.errorMessage = null;
            return true;

        } catch (error) {
            StateStore.data.errorMessage = error.response?.data?.message || 'Oh no! The Sentence could not be saved.';
            return false;
        }
    };

    const resetDialog = async () => {
        data.stagedDialog = cloneDeep(data.originalDialog);
    };

    return {
        data,
        saveDialog,
        resetDialog,
    }
});
