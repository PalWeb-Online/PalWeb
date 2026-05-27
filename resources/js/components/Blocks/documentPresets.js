export const documentPresets = {
    lesson: {
        schemaVersion: 1,
        allowedBlockTypes: ['container', 'text', 'image', 'chart', 'sentence'],
        createDocument: () => ({
            schemaVersion: 1,
            skills: [],
        }),
    },
    activity: {
        schemaVersion: 1,
        allowedBlockTypes: ['text', 'image', 'audio', 'table', 'exercises'],
        createDocument: () => ({
            schemaVersion: 1,
            blocks: [],
        }),
    },
    wiki: {
        schemaVersion: 1,
        allowedBlockTypes: ['container', 'heading', 'text', 'image', 'table', 'chart', 'sentence'],
        createDocument: () => ({
            schemaVersion: 1,
            blocks: [],
        }),
    },
};

export const getDocumentPreset = (key) => {
    return documentPresets[key] ?? documentPresets.wiki;
};
