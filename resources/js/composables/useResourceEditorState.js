import {ref} from "vue";

export function useResourceEditorState({
   initiallyLoading = true,
} = {}) {
    const isSaving = ref(false);
    const isDeleting = ref(false);
    const isLoadingForm = ref(initiallyLoading);

    const withSaving = async (callback) => {
        isSaving.value = true;

        try {
            return await callback();
        } finally {
            isSaving.value = false;
        }
    };

    const withDeleting = async (callback) => {
        isDeleting.value = true;

        try {
            return await callback();
        } finally {
            isDeleting.value = false;
        }
    };

    const withLoadingForm = async (callback) => {
        isLoadingForm.value = true;

        try {
            return await callback();
        } finally {
            isLoadingForm.value = false;
        }
    };

    return {
        isSaving,
        isDeleting,
        isLoadingForm,
        withSaving,
        withDeleting,
        withLoadingForm,
    };
}
