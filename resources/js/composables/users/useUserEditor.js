import {route} from "ziggy-js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useUserLoader} from "./useUserLoader.js";

export function useUserEditor({
                                  userId,
                                  username,
                              }) {
    const userLoader = useUserLoader();

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        userLoader.setUser(model);

        form.name = model?.name ?? '';
        form.username = model?.username ?? '';
        form.ar_name = model?.ar_name ?? '';
        form.home = model?.home ?? '';
        form.bio = model?.bio ?? '';
        form.avatar = model?.avatar ?? '';
        form.avatar_id = model?.avatar_id ?? null;
        form.private = model?.private ?? false;
        form.dialect_id = model?.dialect?.id ?? '';

        defaults();
        clearErrors();
    };

    const editor = useResourceEditor({
        initialForm: {
            name: '',
            username: '',
            ar_name: '',
            home: '',
            bio: '',
            avatar: '',
            avatar_id: null,
            private: false,
            dialect_id: '',
        },
        populateForm,
        extractSavedModel: (response) => response.data.user ?? response.data.data ?? null,
        fetchModel: userLoader.fetchUser,
        resetModel: userLoader.setUser,
        label: 'User',
        getUpdateUrl: (identifier) => route('users.update', identifier),

        // todo: saving based on the username works, but it requires a soft redirect after saving
        //  since you can change your username; it also requires custom extraction of the identifier
        getLoadIdentifier: () => userId.value,
        getSaveIdentifier: () => userLoader.user.value?.username ?? username.value,
        extractSavedIdentifier: (model) => model?.username ?? null,
        afterSave: (response, savedUser) => {
            if (!savedUser?.username || savedUser.username === username.value) return;

            window.history.replaceState({}, '', route('users.edit', savedUser.username));
        },
    });

    return {
        form: editor.form,
        errors: editor.errors,
        isDirty: editor.isDirty,
        recentlySuccessful: editor.recentlySuccessful,
        reset: editor.reset,
        isSaving: editor.isSaving,
        isLoadingForm: editor.isLoadingForm,
        loadForm: editor.loadForm,
        reloadForm: editor.reloadForm,
        saveUser: editor.saveResource,
        user: userLoader.user,
        userNotFound: userLoader.userNotFound,
        canCreateTeacher: userLoader.canCreateTeacher,
    };
}
