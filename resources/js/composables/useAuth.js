import {ref} from "vue";
import {route} from "ziggy-js";
import {useRouter} from "vue-router";
import {useUserStore} from "../stores/UserStore.js";
import {useNotifier} from "./useNotifier.js";
import {syncCsrfToken} from "../utils/csrfToken.js";

export function useAuth() {
    const router = useRouter();

    const UserStore = useUserStore();
    const Notifier = useNotifier();

    const signingOut = ref(false);
    const startingDiscordAuth = ref(false);
    const revokingDiscord = ref(false);

    const notifyFromResponse = (data, fallbackType = 'success') => {
        if (!data?.message) return;

        Notifier.notify({
            message: data.message,
            type: data.success === false ? 'error' : fallbackType,
        });
    };

    const notifyFromError = (error, fallbackMessage = 'Something went wrong.') => {
        Notifier.notify({
            message: error?.response?.data?.message ?? fallbackMessage,
            type: 'error',
        });
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

            Notifier.notify({
                message: error?.response?.data?.message ?? 'Failed to start Discord authentication.',
                type: status === 409 ? 'info' : 'error',
            });

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

            Notifier.notify({
                message: data.message,
                type: data.success ? 'success' : 'error',
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
                Notifier.notify({
                    key: 'user.notifications.signed-out',
                    params: {
                        user: data.user,
                    },
                });
            }, 300);

            router.push({name: 'homepage'});

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
