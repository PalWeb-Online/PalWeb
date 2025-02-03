import { defineStore } from 'pinia';
import { reactive } from 'vue';

export const useNotificationStore = defineStore('notification', () => {
    const notifications = reactive([]);

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

    return { notifications, addNotification, removeNotification };
});
