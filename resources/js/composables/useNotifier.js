import {useI18n} from "vue-i18n";
import {useNotificationStore} from "../stores/NotificationStore.js";

export function useNotifier() {
    const {t, locale} = useI18n();
    const NotificationStore = useNotificationStore();

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

    const resolveMessage = (notification) => {
        if (typeof notification === 'string') {
            return notification;
        }

        if (notification?.key) {
            return t(notification.key, resolveParams(notification.params));
        }

        return notification?.message ?? '';
    };

    const notify = (payload) => {
        const notification = payload?.notification ?? payload;
        const message = resolveMessage(notification);

        if (!message) {
            return;
        }

        NotificationStore.addNotification({
            message,
            type: notification?.type,
            duration: notification?.duration,
        });
    };

    return {
        notify,
    };
}
