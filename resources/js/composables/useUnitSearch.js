import axios from "axios";
import {route} from "ziggy-js";

export function useUnitSearch() {
    const searchUnits = async (q = '') => {
        const response = await axios.get(route('api.units.search'), {
            params: {
                q
            },
        });

        return response.data.data ?? [];
    };

    return {
        searchUnits,
    };
}
