export function useResourceViewer({
                                      fetchModel,
                                      fetchRandomModel = null,
                                      resetModel,
                                      afterLoad = null,
                                      beforeReload = null,
                                      afterReload = null,
                                  }) {
    const load = async (...args) => {
        const model = await fetchModel(...args);

        await afterLoad?.(model, ...args);

        return model;
    };

    const loadRandom = async (options = {}) => {
        if (!fetchRandomModel) {
            throw new Error('fetchRandomModel is required for random resource loading.');
        }

        const model = await fetchRandomModel(options);

        await afterLoad?.(model, options);

        return model;
    };

    const reload = async (...args) => {
        resetModel();

        await beforeReload?.(...args);

        const model = await load(...args);

        await afterReload?.(model, ...args);

        return model;
    };

    const reloadRandom = async (options = {}) => {
        resetModel();

        await beforeReload?.(options);

        const model = await loadRandom(options);

        await afterReload?.(model, options);

        return model;
    };

    return {
        load,
        loadRandom,
        reload,
        reloadRandom,
    };
}
