import {useResourceSearch} from "../resources/useResourceSearch.js";

export function useUnitSearch() {
    const {
        searchResource: searchUnits,
    } = useResourceSearch({
        routeBase: 'api.units.search',
    });

    return {
        searchUnits,
    };
}
