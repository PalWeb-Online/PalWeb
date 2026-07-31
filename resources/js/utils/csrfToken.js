export function syncCsrfToken(token) {
    if (!token) {
        return;
    }

    let meta = document.querySelector('meta[name="csrf-token"]');

    if (!meta) {
        meta = document.createElement('meta');
        meta.name = 'csrf-token';
        document.head.appendChild(meta);
    }

    meta.setAttribute('content', token);
}
