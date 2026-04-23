import {defineStore} from 'pinia';
import {onMounted, reactive, ref} from 'vue';
import {disableWebPush, enableWebPush} from "../push.js";

export const useNotificationStore = defineStore('NotificationStore', () => {
    const notifications = reactive([]);
    const currentBrowserSubscribed = ref(false);
    const currentBrowserSubscription = ref(null);
    const showPushSubscribeHint = ref(false);
    const isUpdatingBrowserSubscription = ref(false);

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

    const setBrowserSubscriptionEnabled = async (enabled) => {
        if (isUpdatingBrowserSubscription.value) {
            return currentBrowserSubscribed.value;
        }

        isUpdatingBrowserSubscription.value = true;

        const previousSubscribed = currentBrowserSubscribed.value;
        const previousSubscription = currentBrowserSubscription.value;

        currentBrowserSubscribed.value = enabled;
        currentBrowserSubscription.value = enabled ? previousSubscription : null;

        try {
            if (enabled) {
                await enableWebPush();
                showPushSubscribeHint.value = false;
            } else {
                await disableWebPush();
            }

            await refreshCurrentBrowserSubscriptionState();

            return currentBrowserSubscribed.value;
        } catch (error) {
            currentBrowserSubscribed.value = previousSubscribed;
            currentBrowserSubscription.value = previousSubscription;

            try {
                await refreshCurrentBrowserSubscriptionState();
            } catch (refreshError) {
                console.error(refreshError);
            }

            throw error;
        } finally {
            isUpdatingBrowserSubscription.value = false;
        }
    };

    const refreshCurrentBrowserSubscriptionState = async () => {
        currentBrowserSubscribed.value = false;
        currentBrowserSubscription.value = null;

        if (!('serviceWorker' in navigator)) {
            return false;
        }

        if (!('PushManager' in window)) {
            return false;
        }

        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();

        currentBrowserSubscription.value = subscription;
        currentBrowserSubscribed.value = !!subscription;

        return currentBrowserSubscribed.value;
    };

    onMounted(async () => {
        await refreshCurrentBrowserSubscriptionState();
        showPushSubscribeHint.value = !currentBrowserSubscribed.value;
    });

    return {
        notifications,
        addNotification,
        removeNotification,
        currentBrowserSubscribed,
        currentBrowserSubscription,
        showPushSubscribeHint,
        refreshCurrentBrowserSubscriptionState,
        setBrowserSubscriptionEnabled,
    };
});
