import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useResourceDelete} from "../resources/useResourceDelete.js";

export function useCard() {
    const NotificationStore = useNotificationStore();

    const {
        isDeleting: isDeletingCard,
        deleteResource: deleteCard,
    } = useResourceDelete({
        routeBase: 'cards',
        label: 'Card',
        onDeleteSuccess: () => {
            router.get(route('card-dealer.cards'));
        },
    });

    const masterCard = (card) => {
        if (!confirm('Are you sure you want to overwrite this Card\'s data to immediately master it?')) return;

        router.post(route('cards.master', card.id));
        NotificationStore.addNotification('Mastered Card!', 'info');
    };

    const toggleSuspend = (card) => {
        router.post(route('cards.suspend', card.id));

        if (card.suspended_at) {
            NotificationStore.addNotification('Restored Card & scheduled it for tomorrow.', 'info');

        } else {
            NotificationStore.addNotification('Suspended Card!', 'info');
        }
    };

    const resetCard = (card) => {
        if (!confirm('Are you sure you want to delete your data for this Card & reset it to a New Card?')) return;

        router.post(route('cards.reset', card.id));
        NotificationStore.addNotification('Reset Card!', 'info');
    };

    return {
        isDeletingCard,
        toggleSuspend,
        masterCard,
        resetCard,
        deleteCard,
    };
}
