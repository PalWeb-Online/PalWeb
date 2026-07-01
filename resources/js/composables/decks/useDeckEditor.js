import {computed} from "vue";
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import {useDeckLoader} from "./useDeckLoader.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useUserStore} from "../../stores/UserStore.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";

export function useDeckEditor({
                                  deckId = null,
                              } = {}) {
    const deckLoader = useDeckLoader();
    const NotificationStore = useNotificationStore();

    const UserStore = useUserStore();
    const author = computed(() => ({
        id: UserStore.user?.id ?? null,
        name: UserStore.user?.name ?? '',
        ar_name: UserStore.user?.ar_name ?? '',
        username: UserStore.user?.username ?? '',
        avatar_url: UserStore.user?.avatar_url ?? '',
    }));

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        deckLoader.setDeck(model);

        form.id = model?.id ?? null;
        form.name = model?.name ?? '';
        form.description = model?.description ?? '';
        form.private = model?.private ?? false;
        form.created_at = model?.created_at ?? null;
        form.author = model?.author ?? author.value;
        form.terms = model?.terms ?? [];

        defaults();
        clearErrors();
    };

    const redirectToEditRoute = (deck = null) => {
        if (deckId.value || !deck?.id) return;

        router.visit(route('deck-master.build', deck.id), {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    };

    const editor = useResourceEditor({
        initialForm: {
            id: null,
            name: '',
            description: '',
            private: false,
            created_at: null,
            author: author.value,
            terms: [],
        },
        populateForm,
        extractSavedModel: (response) => response.data.deck ?? response.data.data ?? null,
        getLoadIdentifier: () => deckId.value,
        fetchModel: (identifier) => deckLoader.fetchDeck(identifier, {
            include: 'edit',
        }),
        resetModel: deckLoader.setDeck,
        label: 'Deck',
        routeBase: 'decks',
        afterSave: (response, savedDeck) => {
            redirectToEditRoute(savedDeck);
        },
    });

    const updatePosition = () => {
        editor.form.terms.forEach((term, index) => {
            term.deckPivot.position = index + 1;
        });
    };

    const insertTerm = (term) => {
        const termExists = editor.form.terms.some(existingTerm => existingTerm.id === term.id);

        if (termExists) {
            NotificationStore.addNotification('This Term is already in the Deck!', 'error');
            return;
        }

        editor.form.terms.push({
            id: term.id,
            term: term.term,
            category: term.category,
            translit: term.translit,
            glosses: term.glosses.map((gloss) => ({
                id: gloss.id,
                gloss: gloss.gloss,
            })),
            deckPivot: {
                gloss_id: term.glosses[0].id,
                position: '',
            },
        });

        updatePosition();
        NotificationStore.addNotification(`Added ${term.term} to the Deck!`);
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
        saveDeck: editor.saveResource,
        deck: deckLoader.deck,
        deckNotFound: deckLoader.deckNotFound,
        insertTerm,
        removeTerm,
        updatePosition,
    };
}
