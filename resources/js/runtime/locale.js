const storageKey = 'palweb.locale';
const pendingSyncStorageKey = 'palweb.locale.pendingSync';
const supportedLocales = ['en', 'es', 'ar'];
const defaultLocale = 'en';

function storage() {
    try {
        return window.localStorage;
    } catch {
        return null;
    }
}

export function normalizeLocale(value) {
    if (!value || typeof value !== 'string') {
        return null;
    }

    const locale = value.toLowerCase().replace('_', '-').split('-')[0];

    return supportedLocales.includes(locale) ? locale : null;
}

function storedLocale() {
    return normalizeLocale(storage()?.getItem(storageKey));
}

function browserLocale() {
    const languages = [
        ...(navigator.languages ?? []),
        navigator.language,
    ];

    return languages.map(normalizeLocale).find(Boolean) ?? null;
}

export function applyLocale(i18n, locale, {persist = true} = {}) {
    const nextLocale = normalizeLocale(locale) ?? defaultLocale;

    i18n.global.locale.value = nextLocale;
    document.documentElement.lang = nextLocale;

    if (persist) {
        storage()?.setItem(storageKey, nextLocale);
    }

    return nextLocale;
}

export function initializeClientLocale(i18n) {
    return applyLocale(
        i18n,
        storedLocale()
            ?? normalizeLocale(document.documentElement.lang)
            ?? browserLocale()
            ?? defaultLocale,
    );
}

export function hasPendingLocalePreference() {
    return Boolean(normalizeLocale(storage()?.getItem(pendingSyncStorageKey)));
}

export function reconcileLocaleWithUser(i18n, user) {
    if (hasPendingLocalePreference()) {
        return i18n.global.locale.value;
    }

    const userLocale = normalizeLocale(user?.language);

    if (!userLocale) {
        return i18n.global.locale.value;
    }

    return applyLocale(i18n, userLocale);
}

export async function flushLocalePreferenceSync(i18n) {
    const pendingLocale = normalizeLocale(storage()?.getItem(pendingSyncStorageKey));

    if (!pendingLocale || !navigator.onLine) {
        return null;
    }

    const {data} = await axios.post(`/api/language/${pendingLocale}`);
    const syncedLocale = applyLocale(i18n, data.locale);

    if (syncedLocale === pendingLocale) {
        storage()?.removeItem(pendingSyncStorageKey);
    }

    return syncedLocale;
}

export async function changeApplicationLocale(i18n, locale) {
    const nextLocale = applyLocale(i18n, locale);

    storage()?.setItem(pendingSyncStorageKey, nextLocale);

    if (!navigator.onLine) {
        return nextLocale;
    }

    try {
        await flushLocalePreferenceSync(i18n);
    } catch (error) {
        console.error('Failed to sync locale preference:', error);
    }

    return nextLocale;
}
