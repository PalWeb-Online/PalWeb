import {useResourceSearch} from "../resources/useResourceSearch.js";

export function useUnitSearch() {
    const {
        searchResource: searchUnits,
    } = useResourceSearch({
        routeName: 'api.units.search',
    });

    return {
        searchUnits,
    };
}
