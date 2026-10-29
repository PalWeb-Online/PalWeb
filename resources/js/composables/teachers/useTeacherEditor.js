import {computed, ref} from "vue";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useRouter} from "vue-router";

export function useTeacherEditor({
                                  user,
                              }) {
    const router = useRouter();
    // normally there would be a use*Loader composable here, but the Teacher model is loaded in
    // with the User model, so we just need to provide the ref & set method

    const teacher = ref(null);

    const setTeacher = (model = null) => {
        teacher.value = model;

        if (user.value) {
            user.value.teacher = model;
        }
    };

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        setTeacher(model);

        form.user_id = user.value?.id ?? null;
        form.email = model?.email ?? '';
        form.bio = model?.bio ?? '';

        defaults();
        clearErrors();
    };

    const editor = useResourceEditor({
        initialForm: {
            user_id: user.value?.id ?? null,
            email: '',
            bio: '',
        },
        populateForm,
        extractSavedModel: (response) => response.data.teacher ?? response.data.data ?? null,
        getLoadIdentifier: () => user.value?.teacher?.id ?? null,
        fetchModel: () => user.value?.teacher ?? null,
        resetModel: setTeacher,
        label: 'teacher',
        routeBase: 'teachers',
        onDeleteSuccess: () => {
            router.push({
                name: 'users.show',
                params: {user: user.value?.username},
            });
        },
    });

    const teacherExists = computed(() => !!teacher.value?.id);

    const clearTeacherForm = () => {
        setTeacher(null);

        editor.form.email = '';
        editor.form.bio = '';
        editor.form.user_id = user.value?.id ?? null;
        editor.defaults();
        editor.clearErrors();
    };

    return {
        form: editor.form,
        errors: editor.errors,
        isDirty: editor.isDirty,
        recentlySuccessful: editor.recentlySuccessful,
        reset: editor.reset,
        isSaving: editor.isSaving,
        isDeleting: editor.isDeleting,
        isLoadingForm: editor.isLoadingForm,
        loadForm: editor.loadForm,
        reloadForm: editor.reloadForm,
        saveTeacher: editor.saveResource,
        deleteTeacher: editor.deleteResource,
        clearTeacherForm,
        teacher,
        teacherExists,
    };
}
