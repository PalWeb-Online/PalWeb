export function pathSegment(value) {
    return encodeURIComponent(String(value));
}

export function apiUrl(path = '') {
    const normalizedPath = path.startsWith('/') ? path : `/${path}`;

    return `/api${normalizedPath}`;
}
