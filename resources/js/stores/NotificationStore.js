import {defineStore} from 'pinia';
import {reactive, ref} from 'vue';

export const useNotificationStore = defineStore('NotificationStore', () => {
    const notifications = reactive([]);
    const currentBrowserSubscribed = ref(false);
    const currentBrowserSubscription = ref(null);

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

    return {
        notifications,
        addNotification,
        removeNotification,
        currentBrowserSubscribed,
        currentBrowserSubscription,
        refreshCurrentBrowserSubscriptionState,
    };
});
