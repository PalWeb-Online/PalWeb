import {ref} from "vue";
import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useUnitLoader() {
    const lessons = ref(null);

    const {
        model: unit,
        notFound: unitNotFound,
        isLoading: isLoadingUnit,
        setModel: setUnit,
        fetchModel: fetchUnit,
    } = useResourceLoader({
        getUrl: (unitId) => route('api.units.fetch', unitId),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.unit ?? null,
        extractMeta: (response) => ({
            lessons: response.data.lessons ?? null,
        }),
        onSetModel: (meta = {}) => {
            lessons.value = meta.lessons ?? null;
        },
    });

    return {
        unit,
        unitNotFound,
        isLoadingUnit,
        lessons,
        setUnit,
        fetchUnit,
    };
}
