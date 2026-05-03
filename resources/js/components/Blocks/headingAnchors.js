export const slugifyHeadingTitle = (value) => {
    return String(value ?? '')
        .trim()
        .toLowerCase()
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

export const getHeadingAnchorId = (block) => {
    const titleSlug = slugifyHeadingTitle(block?.title) || 'heading';
    const idSlug = slugifyHeadingTitle(block?.id) || 'unknown';

    return `${titleSlug}-${idSlug}`;
};
