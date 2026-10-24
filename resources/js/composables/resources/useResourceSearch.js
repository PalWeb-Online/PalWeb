export function useResourceSearch({
                                      url,
                                      params = () => ({}),
                                  }) {
    const searchResource = async (q = '') => {
        const response = await axios.get(url, {
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
