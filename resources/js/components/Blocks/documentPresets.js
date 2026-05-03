export const documentPresets = {
    lesson: {
        schemaVersion: 1,
        allowedBlockTypes: ['container', 'text', 'audio', 'chart', 'table', 'sentence'],
        createDocument: () => ({
            schemaVersion: 1,
            skills: [],
        }),
    },
    activity: {
        schemaVersion: 1,
        allowedBlockTypes: ['text', 'audio', 'table', 'exercises'],
        createDocument: () => ({
            schemaVersion: 1,
            blocks: [],
        }),
    },
    wiki: {
        schemaVersion: 1,
        allowedBlockTypes: ['container', 'heading', 'text', 'chart', 'sentence'],
        createDocument: () => ({
            schemaVersion: 1,
            blocks: [],
        }),
    },
};

export const getDocumentPreset = (key) => {
    return documentPresets[key] ?? documentPresets.wiki;
};
