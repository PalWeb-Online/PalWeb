import {useResourceSearch} from "../resources/useResourceSearch.js";

export function useUnitSearch() {
    const {
        searchResource: searchUnits,
    } = useResourceSearch({
        url: '/api/units/search',
    });

    return {
        searchUnits,
    };
}
