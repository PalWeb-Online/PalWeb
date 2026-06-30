import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import {useTermLoader} from "./useTermLoader.js";
import {useResourceEditor} from "../resources/useResourceEditor.js";

export function useTermEditor({
                                  termId = null,
                              } = {}) {
    const termLoader = useTermLoader();

    const makeAttribute = () => ({
        id: null,
        attribute: '',
    });

    const makePronunciation = () => ({
        translit: '',
        phonemic: '//',
        phonetic: '[]',
        dialect_id: null,
        borrowed: 0,
    });

    const makeGloss = ({withAttribute = false} = {}) => ({
        gloss: '',
        attributes: withAttribute ? [makeAttribute()] : [],
    });

    const populateForm = (model = null, {form, defaults, clearErrors}) => {
        termLoader.setTerms(model ? [model] : []);

        form.id = model?.id ?? null;
        form.term = model?.term ?? '';
        form.spellings = model?.spellings ?? [];
        form.category = model?.category ?? '';
        form.attributes = model?.attributes ?? [];
        form.etymology = model?.etymology ?? {
            type: '', source: '',
        };
        form.root = model?.root ?? {
            root: ''
        };
        form.patterns = model?.patterns ?? [];
        form.pronunciations = model?.pronunciations ?? [makePronunciation()];
        form.glosses = model?.glosses ?? [makeGloss({
            withAttribute: form.category === 'verb',
        })];
        form.inflections = model?.inflections ?? [];
        form.relatives = model?.relatives ?? [];
        form.image = model?.image ?? '';
        form.usage = model?.usage ?? '';

        defaults();
        clearErrors();
    };

    const redirectToEditRoute = (term = null) => {
        if (termId.value || !term?.id) return;

        router.visit(route('word-logger.term', term.id), {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    };

    const editor = useResourceEditor({
        initialForm: {
            id: null,
            term: '',
            spellings: [],
            category: '',
            attributes: [],
            etymology: {
                type: '',
                source: '',
            },
            root: {
                root: '',
            },
            patterns: [],
            pronunciations: [],
            glosses: [],
            inflections: [],
            relatives: [],
            image: '',
            usage: '',
        },
        populateForm,
        extractSavedModel: (response) => response.data.term ?? response.data.data ?? null,
        getLoadIdentifier: () => termId.value,
        fetchModel: async (termId) => {
            const terms = await termLoader.fetchTerms(termId, {
                include: 'edit',
            });

            return terms?.[0] ?? null;
        },
        resetModel: termLoader.setTerms,
        label: 'Term',
        routeBase: 'terms',
        afterSave: (response, savedTerm) => {
            redirectToEditRoute(savedTerm);
        },
    });

    const addSpelling = () => {
        editor.form.spellings.push({
            spelling: '',
        });
    };

    const addAttribute = (model, index = null) => {
        if (model === 'term') {
            editor.form.attributes.push(makeAttribute());

        } else if (model === 'gloss') {
            editor.form.glosses[index].attributes.push(makeAttribute());
        }
    };

    const addPattern = () => {
        editor.form.patterns.push({
            type: '', form: '', pattern: '',
        });
    };

    const addPronunciation = () => {
        editor.form.pronunciations.push(makePronunciation());
    };

    const addGloss = () => {
        editor.form.glosses.push(makeGloss({
            withAttribute: editor.form.category === 'verb',
        }));
    };

    const addInflection = () => {
        editor.form.inflections.push({
            inflection: '', translit: '', form: '',
        });
    };

    const insertRelative = (relative) => {
        editor.form.relatives.push({
            slug: relative.slug, type: '',
        });
    };

    const removeItem = (index, fieldType) => {
        fieldType.splice(index, 1);
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
        saveTerm: editor.saveResource,
        term: termLoader.primaryTerm,
        termsNotFound: termLoader.termsNotFound,
        addSpelling,
        addAttribute,
        addPattern,
        addPronunciation,
        addGloss,
        addInflection,
        insertRelative,
        removeItem,
    };
}
