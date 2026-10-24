import {useRouter} from "vue-router";
import {useNotifier} from "../useNotifier.js";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useI18n} from "vue-i18n";

export function useCard() {
    const {t} = useI18n();
    const router = useRouter();
    const Notifier = useNotifier();

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

        await axios.post(`/api/cards/${card.id}/master`);
        Notifier.notify({
            key: 'card.notifications.master-success',
            type: 'info',
        });
    };

    const toggleSuspend = async (card) => {
        await axios.post(`/api/cards/${card.id}/suspend`);

        if (card.suspended_at) {
            Notifier.notify({
                key: 'card.notifications.restore-success',
                type: 'info',
            });

        } else {
            Notifier.notify({
                key: 'card.notifications.suspend-success',
                type: 'info',
            });
        }
    };

    const resetCard = async (card) => {
        if (!confirm(t('card.notifications.reset-confirm'))) return;

        await axios.post(`/api/cards/${card.id}/reset`);
        Notifier.notify({
            key: 'card.notifications.reset-success',
            type: 'info',
        });
    };

    return {
        isDeletingCard,
        toggleSuspend,
        masterCard,
        resetCard,
        deleteCard,
    };
}
