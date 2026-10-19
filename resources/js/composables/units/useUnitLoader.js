import {ref} from "vue";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useUnitLoader() {
    const lessons = ref(null);

    const {
        model: unit,
        notFound: unitNotFound,
        isLoading: isLoadingUnit,
        setModel: setUnit,
        fetchShowModel: fetchShowUnit,
        fetchEditModel: fetchEditUnit,
    } = useResourceLoader({
        routeBase: 'units',
        routeParam: 'unit',
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
        fetchShowUnit,
        fetchEditUnit,
    };
}
