import {route} from "ziggy-js";
import {useRouter} from "vue-router";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useI18n} from "vue-i18n";

export function useCard() {
    const {t} = useI18n();
    const router = useRouter();
    const NotificationStore = useNotificationStore();

    const {
        isDeleting: isDeletingCard,
        deleteResource: deleteCard,
    } = useResourceDelete({
        label: 'card',
        routeBase: 'cards',
        onDeleteSuccess: () => {
            router.push({name: 'card-dealer.cards'});
        },
    });

    const masterCard = async (card) => {
        if (!confirm(t('card.notifications.master-confirm'))) return;

        await axios.post(route('cards.master', card.id));
        NotificationStore.addNotification(t('card.notifications.master-success'), 'info');
    };

    const toggleSuspend = async (card) => {
        await axios.post(route('cards.suspend', card.id));

        if (card.suspended_at) {
            NotificationStore.addNotification(t('card.notifications.restore-success'), 'info');

        } else {
            NotificationStore.addNotification(t('card.notifications.suspend-success'), 'info');
        }
    };

    const resetCard = async (card) => {
        if (!confirm(t('card.notifications.reset-confirm'))) return;

        await axios.post(route('cards.reset', card.id));
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
