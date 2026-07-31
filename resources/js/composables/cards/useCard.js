import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useI18n} from "vue-i18n";

export function useCard() {
    const {t} = useI18n();
    const NotificationStore = useNotificationStore();

    const {
        isDeleting: isDeletingCard,
        deleteResource: deleteCard,
    } = useResourceDelete({
        label: 'card',
        routeBase: 'cards',
        onDeleteSuccess: () => {
            router.get(route('card-dealer.cards'));
        },
    });

    const masterCard = (card) => {
        if (!confirm(t('card.notifications.master-confirm'))) return;

        router.post(route('cards.master', card.id));
        NotificationStore.addNotification(t('card.notifications.master-success'), 'info');
    };

    const toggleSuspend = (card) => {
        router.post(route('cards.suspend', card.id));

        if (card.suspended_at) {
            NotificationStore.addNotification(t('card.notifications.restore-success'), 'info');

        } else {
            NotificationStore.addNotification(t('card.notifications.suspend-success'), 'info');
        }
    };

    const resetCard = (card) => {
        if (!confirm(t('card.notifications.reset-confirm'))) return;

        router.post(route('cards.reset', card.id));
        NotificationStore.addNotification(t('card.notifications.reset-success'), 'info');
    };

    return {
        isDeletingCard,
        toggleSuspend,
        masterCard,
        resetCard,
        deleteCard,
    };
}
