const localeStorageKey = 'palweb.locale';
const pendingLocaleSyncStorageKey = 'palweb.locale.pendingSync';
export const supportedLocales = ['en', 'es', 'ar'];
export const defaultLocale = 'en';

const themeStorageKey = 'palweb.theme';
const pendingThemeSyncStorageKey = 'palweb.theme.pendingSync';
export const themes = ['PalWebOS', 'Watermelon', 'Nabatean', 'Jerusalem', 'Falastin', 'Msaxxan'];
export const defaultTheme = 'Msaxxan';

function storage() {
    try {
        return window.localStorage;
    } catch {
        return null;
    }
}

function getStoredValue(key) {
    return storage()?.getItem(key) ?? null;
}

function setStoredValue(key, value) {
    storage()?.setItem(key, value);
}

function removeStoredValue(key) {
    storage()?.removeItem(key);
}

export function normalizeLocale(value) {
    if (!value || typeof value !== 'string') {
        return null;
    }

    const locale = value.toLowerCase().replace('_', '-').split('-')[0];

    return supportedLocales.includes(locale) ? locale : null;
}

export function normalizeTheme(value) {
    return themes.includes(value) ? value : null;
}

function storedLocale() {
    return normalizeLocale(getStoredValue(localeStorageKey));
}

function storedTheme() {
    return normalizeTheme(getStoredValue(themeStorageKey));
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
        setStoredValue(localeStorageKey, nextLocale);
    }

    return nextLocale;
}

export function applyTheme(theme, {persist = true} = {}) {
    const nextTheme = normalizeTheme(theme) ?? defaultTheme;

    document.documentElement.classList.remove(...themes.map((value) => `theme-${value}`));
    document.documentElement.classList.add(`theme-${nextTheme}`);

    if (persist) {
        setStoredValue(themeStorageKey, nextTheme);
    }

    return nextTheme;
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

export function initializeClientTheme() {
    return applyTheme(
        storedTheme()
            ?? normalizeTheme([...document.documentElement.classList]
                .find((className) => className.startsWith('theme-'))
                ?.replace('theme-', ''))
            ?? defaultTheme,
    );
}

export function initializeUserInterfacePreferences(i18n) {
    return {
        locale: initializeClientLocale(i18n),
        theme: initializeClientTheme(),
    };
}

export function hasPendingLocalePreference() {
    return Boolean(normalizeLocale(getStoredValue(pendingLocaleSyncStorageKey)));
}

export function hasPendingThemePreference() {
    return Boolean(normalizeTheme(getStoredValue(pendingThemeSyncStorageKey)));
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

export function reconcileThemeWithUser(user) {
    if (hasPendingThemePreference()) {
        return storedTheme() ?? defaultTheme;
    }

    const userTheme = normalizeTheme(user?.preferences?.theme);

    if (!userTheme) {
        return storedTheme() ?? defaultTheme;
    }

    return applyTheme(userTheme);
}

export function reconcileUserInterfacePreferencesWithUser(i18n, user) {
    return {
        locale: reconcileLocaleWithUser(i18n, user),
        theme: reconcileThemeWithUser(user),
    };
}

export async function flushLocalePreferenceSync(i18n) {
    const pendingLocale = normalizeLocale(getStoredValue(pendingLocaleSyncStorageKey));

    if (!pendingLocale || !navigator.onLine) {
        return null;
    }

    const {data} = await axios.post(`/api/language/${pendingLocale}`);
    const syncedLocale = applyLocale(i18n, data.locale);

    if (syncedLocale === pendingLocale) {
        removeStoredValue(pendingLocaleSyncStorageKey);
    }

    return syncedLocale;
}

export async function flushThemePreferenceSync(user = null) {
    const pendingTheme = normalizeTheme(getStoredValue(pendingThemeSyncStorageKey));

    if (!pendingTheme || !navigator.onLine || !user) {
        return null;
    }

    const {data} = await axios.patch('/api/auth/preferences', {
        theme: pendingTheme,
    });

    const syncedTheme = applyTheme(data.preferences?.theme ?? pendingTheme);

    if (syncedTheme === pendingTheme) {
        removeStoredValue(pendingThemeSyncStorageKey);
    }

    return {
        preferences: data.preferences,
        theme: syncedTheme,
    };
}

export async function flushUserInterfacePreferenceSync(i18n, user = null) {
    const [locale, theme] = await Promise.all([
        flushLocalePreferenceSync(i18n),
        flushThemePreferenceSync(user),
    ]);

    return {
        locale,
        theme,
    };
}

export async function changeApplicationLocale(i18n, locale) {
    const nextLocale = applyLocale(i18n, locale);

    setStoredValue(pendingLocaleSyncStorageKey, nextLocale);

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

export async function changeApplicationTheme(theme, user = null) {
    const nextTheme = applyTheme(theme);

    setStoredValue(pendingThemeSyncStorageKey, nextTheme);

    if (!navigator.onLine || !user) {
        return {
            preferences: null,
            theme: nextTheme,
        };
    }

    try {
        return await flushThemePreferenceSync(user);
    } catch (error) {
        console.error('Failed to sync theme preference:', error);

        return {
            preferences: null,
            theme: nextTheme,
        };
    }
}
