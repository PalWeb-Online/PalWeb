export function useResourceViewer({
                                      fetchModel,
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

    const reload = async (...args) => {
        resetModel();

        await beforeReload?.(...args);

        const model = await load(...args);

        await afterReload?.(model, ...args);

        return model;
    };

    return {
        load,
        reload,
    };
}
