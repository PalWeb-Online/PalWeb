import {useRouter} from "vue-router";
import {useDialogLoader} from "./useDialogLoader.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useNotifier} from "../useNotifier.js";
import {useI18n} from "vue-i18n";

export function useDialogEditor({
                                dialogId = null,
                            } = {}) {
    const {t} = useI18n();
    const router = useRouter();
    const dialogLoader = useDialogLoader();
    const Notifier = useNotifier();

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

        router.replace({
            name: 'speech-maker.dialog',
            params: {dialog: dialog.id},
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
        fetchModel: dialogLoader.fetchEditDialog,
        resetModel: dialogLoader.setDialog,
        label: 'dialog',
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
            Notifier.notify({
                key: 'forms.notifications.model-already-present',
                params: {
                    model: t('actions.models.sentence'),
                    target: t('actions.models.dialog'),
                },
                type: 'error',
            });
            return;

        } else if (sentence.dialog) {
            Notifier.notify({
                key: 'forms.notifications.model-already-present',
                params: {
                    model: t('actions.models.sentence'),
                    target: t('dialog.key'),
                },
                type: 'error',
            });
            return;
        }

        editor.form.sentences.push({
            id: sentence.id,
            sentence: sentence.sentence,
            translit: sentence.translit,
            trans: sentence.trans,
        });

        updatePosition();
        Notifier.notify({
            key: 'forms.notifications.model-added',
            params: {
                model: t('actions.models.sentence'),
                target: t('actions.models.dialog'),
            },
        });
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
