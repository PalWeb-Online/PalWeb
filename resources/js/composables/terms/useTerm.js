import {computed, isRef, reactive, toValue, ref, watch} from 'vue';
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useRouter} from "vue-router";

export function useTerm(input, options = {}) {
    const router = useRouter();
    const term = reactive({});
    const hasSource = arguments.length > 0;
    const isLoading = ref(hasSource && (options.loadingWhenEmpty ?? isRef(input)));

    const resolveSource = () => {
        if (!hasSource) {
            return null;
        }

        const value = toValue(input);

        if (!value) {
            return null;
        }

        if (Object.hasOwn(value, 'model')) {
            return toValue(value.model);
        }

        return value;
    };

    const replaceTerm = (newTerm) => {
        Object.keys(term).forEach((key) => {
            delete term[key];
        });

        Object.assign(term, newTerm);
    };

    const {
        isDeleting: isDeletingTerm,
        deleteResource: deleteTerm,
    } = useResourceDelete({
        label: 'term',
        routeBase: 'terms',
        onDeleteSuccess: () => {
            router.push({name: 'terms.index'});
        },
    });

    const variants = computed(() =>
        term.relatives?.filter(relative => relative.type === 'variant') ?? [],
    );

    const references = computed(() =>
        term.relatives?.filter(relative => relative.type === 'reference') ?? [],
    );

    const derivatives = computed(() =>
        term.relatives?.filter(relative => ['ap', 'pp', 'vn'].includes(relative.type)) ?? [],
    );

    const source = computed(() =>
        term.relatives?.find(relative => relative.type === 'source'),
    );

    const hostForms = computed(() =>
        term.inflections?.filter(inflection => ['genitive', 'accusative'].includes(inflection.form)) ?? [],
    );

    const responseForms = computed(() =>
        term.inflections?.filter(inflection => inflection.form === 'resp') ?? [],
    );

    const constructForms = computed(() =>
        term.inflections?.filter(inflection => inflection.form === 'cnst') ?? [],
    );

    const inflections = computed(() =>
        term.inflections?.filter(inflection => !['cnst', 'resp', 'genitive', 'accusative'].includes(inflection.form)) ?? [],
    );

    watch(resolveSource,
        (newTerm) => {
            if (!newTerm) {
                replaceTerm({});
                isLoading.value = options.loadingWhenEmpty ?? isRef(input);
                return;
            }

            replaceTerm(newTerm);
            isLoading.value = false;
        },
        {deep: true, immediate: true}
    );

    return {
        term,
        variants,
        references,
        derivatives,
        source,
        hostForms,
        responseForms,
        constructForms,
        inflections,
        isLoading,
        isDeletingTerm,
        deleteTerm
    };
}
