import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import {useDialogLoader} from "./useDialogLoader.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";

export function useDialogEditor({
                                    dialogId = null,
                                } = {}) {
    const dialogLoader = useDialogLoader();
    const NotificationStore = useNotificationStore();

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        dialogLoader.setDialog(model);

        form.id = model?.id ?? null;
        form.title = model?.title ?? '';
        form.description = model?.description ?? '';
        form.media = model?.media ?? '';
        form.sentences = model?.sentences ?? [];
        form.published = model?.published ?? false;

        defaults();
        clearErrors();
    };

    const redirectToEditRoute = (dialog = null) => {
        if (dialogId.value || !dialog?.id) return;

        router.visit(route('speech-maker.dialog', dialog.id), {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    };

    const editor = useResourceEditor({
        initialForm: {
            id: null,
            title: '',
            description: '',
            media: '',
            sentences: [],
            published: false,
        },
        populateForm,
        extractSavedModel: (response) => response.data.dialog ?? response.data.data ?? null,
        getLoadIdentifier: () => dialogId.value,
        fetchModel: (identifier) => dialogLoader.fetchDialog(identifier, {
            include: 'edit',
        }),
        resetModel: dialogLoader.setDialog,
        label: 'Dialog',
        routeBase: 'dialogs',
        afterSave: (response, savedDialog) => {
            redirectToEditRoute(savedDialog);
        },
    });

    const updatePosition = () => {
        editor.form.sentences.forEach((sentence, index) => {
            sentence.position = index + 1;
        });
    }

    const insertSentence = async (sentence) => {
        const sentenceExists = editor.form.sentences.some(existingSentence => existingSentence.id === sentence.id);

        if (sentenceExists) {
            NotificationStore.addNotification('This Sentence is already in the Dialog!', 'error');
            return;

        } else if (sentence.dialog) {
            NotificationStore.addNotification('This Sentence is already in a Dialog!', 'error');
            return;
        }

        editor.form.sentences.push({
            id: sentence.id,
            sentence: sentence.sentence,
            translit: sentence.translit,
            trans: sentence.trans,
        });

        updatePosition();
        NotificationStore.addNotification(`Added ${sentence.sentence} to the Dialog!`);
    }

    const removeSentence = (index) => {
        editor.form.sentences.splice(index, 1);
        updatePosition();
    }

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
        saveDialog: editor.saveResource,
        dialog: dialogLoader.dialog,
        dialogNotFound: dialogLoader.dialogNotFound,
        insertSentence,
        removeSentence,
        updatePosition,
    };
}
