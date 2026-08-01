import {defineStore} from 'pinia';
import {onMounted, reactive, ref} from 'vue';
import {disableWebPush, enableWebPush} from "../push.js";
import {useI18n} from "vue-i18n";

export const useNotificationStore = defineStore('NotificationStore', () => {
    const {t, locale} = useI18n();
    const notifications = reactive([]);
    const currentBrowserSubscribed = ref(false);
    const currentBrowserSubscription = ref(null);
    const showPushSubscribeHint = ref(false);
    const isUpdatingBrowserSubscription = ref(false);

    const localizedName = (value) => {
        if (!value || typeof value !== 'object') {
            return value;
        }

        if ('ar_name' in value || 'name' in value) {
            return locale.value === 'ar'
                ? value.ar_name ?? value.name
                : value.name;
        }

        return value;
    };

    const resolveParams = (params = {}) => {
        return Object.fromEntries(
            Object.entries(params).map(([key, value]) => [
                key,
                localizedName(value),
            ])
        );
    };

    const resolveMessage = (payload) => {
        if (typeof payload === 'string') {
            return payload;
        }

        if (payload?.key) {
            return t(payload.key, resolveParams(payload.params));
        }

        return payload?.message ?? '';
    };

    const notify = (payload) => {
        const message = resolveMessage(payload);

        if (!message) {
            return;
        }

        const type = typeof payload === 'object'
            ? payload.type ?? 'success'
            : 'success';

        const duration = typeof payload === 'object'
            ? payload.duration ?? 3000
            : 3000;

        addNotification(message, type, duration);
    };

    const addNotification = (message, type = 'success', duration = 3000) => {
        const id = Date.now();
        notifications.push({ id, message, type });

        setTimeout(() => {
            removeNotification(id);
        }, duration);
    };

    const removeNotification = (id) => {
        const index = notifications.findIndex((notification) => notification.id === id);
        if (index !== -1) {
            notifications.splice(index, 1);
        }
    };

    const refreshBrowserSubscriptionState = async () => {
        currentBrowserSubscribed.value = false;
        currentBrowserSubscription.value = null;

        if (!('serviceWorker' in navigator)) {
            showPushSubscribeHint.value = false;
            return false;
        }

        if (!('PushManager' in window)) {
            showPushSubscribeHint.value = false;
            return false;
        }

        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();

        currentBrowserSubscription.value = subscription;
        currentBrowserSubscribed.value = !!subscription;

        showPushSubscribeHint.value = !currentBrowserSubscribed.value;

        return currentBrowserSubscribed.value;
    };

    const toggleBrowserSubscription = async (enabled) => {
        if (isUpdatingBrowserSubscription.value) {
            return currentBrowserSubscribed.value;
        }

        isUpdatingBrowserSubscription.value = true;

        const previousSubscribed = currentBrowserSubscribed.value;
        const previousSubscription = currentBrowserSubscription.value;
        const previousHintVisible = showPushSubscribeHint.value;

        currentBrowserSubscribed.value = enabled;
        currentBrowserSubscription.value = enabled ? previousSubscription : null;

        try {
            enabled ? await enableWebPush() : await disableWebPush();
            await refreshBrowserSubscriptionState();

            return currentBrowserSubscribed.value;
        } catch (error) {
            currentBrowserSubscribed.value = previousSubscribed;
            currentBrowserSubscription.value = previousSubscription;
            showPushSubscribeHint.value = previousHintVisible;

            try {
                await refreshBrowserSubscriptionState();
            } catch (refreshError) {
                console.error(refreshError);
            }

            throw error;

        } finally {
            isUpdatingBrowserSubscription.value = false;
        }
    };

    onMounted(async () => {
        await refreshBrowserSubscriptionState();
    });

    return {
        notifications,
        notify,
        addNotification,
        removeNotification,
        currentBrowserSubscribed,
        currentBrowserSubscription,
        showPushSubscribeHint,
        refreshBrowserSubscriptionState,
        toggleBrowserSubscription,
    };
});
