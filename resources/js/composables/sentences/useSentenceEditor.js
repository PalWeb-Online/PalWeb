import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import {useSentenceLoader} from "./useSentenceLoader.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";

export function useSentenceEditor({
                                      sentenceId = null,
                                      initialDialog = null,
                                  } = {}) {
    const sentenceLoader = useSentenceLoader();
    const NotificationStore = useNotificationStore();

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        sentenceLoader.setSentence(model);

        form.id = model?.id ?? null;
        form.sentence = model?.sentence ?? '';
        form.translit = model?.translit ?? '';
        form.trans = model?.trans ?? '';
        form.dialog = model?.dialog ?? initialDialog.value ?? {};
        form.speaker = model?.speaker ?? '';
        form.position = model?.position ?? '';
        form.terms = (model?.terms ?? []).map((term) => makeListItem(term));

        // if a new Sentence is created within a Dialog
        if (!model?.id && initialDialog.value?.id) {
            form.position = initialDialog.value.sentences_count + 1;
        }

        defaults();
        clearErrors();
    };

    const redirectToEditRoute = (sentence = null) => {
        if (sentenceId.value || !sentence?.id) return;

        router.visit(route('speech-maker.sentence', sentence.id), {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    };

    const editor = useResourceEditor({
        initialForm: {
            id: null,
            sentence: '',
            translit: '',
            trans: '',
            dialog: initialDialog.value ?? {},
            speaker: '',
            position: '',
            terms: [],
        },
        populateForm,
        extractSavedModel: (response) => response.data.sentence ?? response.data.data ?? null,
        getLoadIdentifier: () => sentenceId.value,
        fetchModel: (identifier) => sentenceLoader.fetchSentence(identifier, {
            include: 'edit',
        }),
        resetModel: sentenceLoader.setSentence,
        label: 'Sentence',
        routeBase: 'sentences',
        afterSave: (response, savedSentence) => {
            redirectToEditRoute(savedSentence);
        },
    });

    const makeListItem = (term = {}) => ({
        ...term,
        sentencePivot: {
            uuid: term.sentencePivot?.uuid || crypto.randomUUID(),
            term_id: term.id ?? null,
            gloss_id: term.sentencePivot?.gloss_id ?? null,
            sent_term: term.sentencePivot?.sent_term ?? '',
            sent_translit: term.sentencePivot?.sent_translit ?? '',
            position: term.sentencePivot?.position ?? '',
            toggleable: term.sentencePivot?.toggleable ?? false,
        },
    });


    const updatePosition = () => {
        editor.form.terms.forEach((term, index) => {
            term.sentencePivot.position = index + 1;
        });
    };

    const addTerm = () => {
        editor.form.terms.push(
            makeListItem({
                sentencePivot: {
                    position: editor.form.terms.length + 1,
                },
            })
        );

        updatePosition();
    };

    const insertTerm = (term) => {
        editor.form.terms.push(
            makeListItem({
                id: term.id,
                term: term.term,
                category: term.category,
                translit: term.translit,
                glosses: term.glosses.map((gloss) => ({
                    id: gloss.id,
                    gloss: gloss.gloss,
                })),
                sentencePivot: {
                    gloss_id: term.glosses[0]?.id ?? null,
                    sent_term: term.term,
                    sent_translit: term.translit,
                    position: editor.form.terms.length + 1,
                    toggleable: false,
                },
            })
        );

        updatePosition();
        NotificationStore.addNotification(`Added ${term.term} to the Sentence!`);
    };

    const removeTerm = (index) => {
        editor.form.terms.splice(index, 1);
        updatePosition();
    };

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
        saveSentence: editor.saveResource,
        sentence: sentenceLoader.sentence,
        sentenceNotFound: sentenceLoader.sentenceNotFound,
        addTerm,
        insertTerm,
        removeTerm,
        updatePosition
    };
}
