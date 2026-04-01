<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useNotificationStore} from "../../stores/NotificationStore.js";

const NotificationStore = useNotificationStore();

const props = defineProps({
    model: Object,
});

const masterCard = () => {
    if (!confirm('Are you sure you want to overwrite this Card\'s data to immediately master it?')) return;

    router.post(route('cards.master', props.model.id));
    NotificationStore.addNotification('Mastered Card!', 'info');
}

const toggleSuspend = () => {
    router.post(route('cards.suspend', props.model.id));

    if (props.model.suspended_at) {
        NotificationStore.addNotification('Restored Card & scheduled it for tomorrow.', 'info');

    } else {
        NotificationStore.addNotification('Suspended Card!', 'info');
    }
}

const resetCard = () => {
    if (!confirm('Are you sure you want to delete your data for this Card & reset it to a New Card?')) return;

    router.post(route('cards.reset', props.model.id));
    NotificationStore.addNotification('Reset Card!', 'info');
}

const deleteCard = () => {
    if (!confirm('Are you sure you want to delete this Card?')) return;

    router.delete(route('cards.destroy', props.model.id));
};
</script>

<template>
        <button @click="masterCard" role="menuitem" tabindex="-1">
            Master Card
        </button>
        <button @click="toggleSuspend" role="menuitem" tabindex="-1">
            {{ model.suspended_at ? 'Restore' : 'Suspend' }} Card
        </button>
        <button @click="resetCard" role="menuitem" tabindex="-1">
            Reset Card
        </button>
        <button @click="deleteCard" role="menuitem" tabindex="-1">
            Delete Card
        </button>
</template>
