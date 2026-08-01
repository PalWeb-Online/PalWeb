import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useI18n} from "vue-i18n";
import {useUserStore} from "../stores/UserStore.js";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {syncCsrfToken} from "../utils/csrfToken.js";

export function useAuth() {
    const {t, locale} = useI18n();

    const UserStore = useUserStore();
    const NotificationStore = useNotificationStore();

    const signingOut = ref(false);
    const startingDiscordAuth = ref(false);
    const revokingDiscord = ref(false);

    const notifyFromResponse = (data, fallbackType = 'success') => {
        if (!data?.message) return;

        NotificationStore.addNotification(
            data.message,
            data.success === false ? 'error' : fallbackType,
        );
    };

    const notifyFromError = (error, fallbackMessage = 'Something went wrong.') => {
        NotificationStore.addNotification(
            error?.response?.data?.message ?? fallbackMessage,
            'error',
        );
    };

    const startDiscordAuth = async () => {
        if (startingDiscordAuth.value) return;

        startingDiscordAuth.value = true;

        try {
            const {data} = await axios.get(route('auth.discord'));

            if (data.redirect_url) {
                window.location.href = data.redirect_url;
                return;
            }

            notifyFromResponse(data, 'info');

        } catch (error) {
            const status = error?.response?.status;

            NotificationStore.addNotification(
                error?.response?.data?.message ?? 'Failed to start Discord authentication.',
                status === 409 ? 'info' : 'error',
            );

        } finally {
            startingDiscordAuth.value = false;
        }
    };

    const revokeDiscord = async () => {
        if (revokingDiscord.value) return;

        revokingDiscord.value = true;

        try {
            const {data} = await axios.post(route('auth.discord.revoke'));

            if (data.user) {
                UserStore.setUser(data.user);
            }

            NotificationStore.addNotification(
                data.message,
                data.success ? 'success' : 'error',
            );

            router.reload({
                only: ['auth'],
            });

        } catch (error) {
            notifyFromError(error, 'Failed to disconnect Discord account.');

        } finally {
            revokingDiscord.value = false;
        }
    };

    const signOut = async ({afterSignOut = null} = {}) => {
        if (signingOut.value) return;

        signingOut.value = true;

        try {
            const {data} = await axios.post(route('signout'));

            syncCsrfToken(data.csrf_token);

            UserStore.clearUser();

            await afterSignOut?.(data);

            setTimeout(() => {
                NotificationStore.addNotification(t('user.notifications.signed-out', {
                    user: locale.value === 'ar' ? data.user.ar_name : data.user.name,
                }));
            }, 300);

            router.get(route('homepage'));

        } finally {
            signingOut.value = false;
        }
    };

    return {
        signingOut,
        startingDiscordAuth,
        revokingDiscord,
        startDiscordAuth,
        revokeDiscord,
        signOut,
    };
}
