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
        addNotification,
        removeNotification,
        currentBrowserSubscribed,
        currentBrowserSubscription,
        showPushSubscribeHint,
        refreshBrowserSubscriptionState,
        toggleBrowserSubscription,
    };
});
