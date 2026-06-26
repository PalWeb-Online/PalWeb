import {route} from "ziggy-js";

export function useResourceSearch({
                                      routeName,
                                      params = () => ({}),
                                  }) {
    const searchResource = async (q = '') => {
        const response = await axios.get(route(routeName), {
            params: {
                q,
                ...params(q),
            },
        });

        return response.data.results ?? [];
    };

    return {
        searchResource,
    };
}
