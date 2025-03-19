<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useActions} from "../composables/Actions.js";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {useUserStore} from "../stores/UserStore.js";

const props = defineProps({
    model: Object,
});

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const deleteDialog = () => {
    if (!confirm('Are you sure you want to delete this Dialog?')) return;

    router.delete(route('dialogs.destroy', props.model.id), {
        onSuccess: () => {
            NotificationStore.addNotification('The Dialog has been deleted!');
        }
    });
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <Link :href="route('dialogs.show', model.id)">View Dialog</Link>

            <template v-if="UserStore.isAdmin">
                <Link :href="route('speech-maker.dialog', model.id)">Edit Dialog</Link>
                <button @click="deleteDialog">Delete Dialog</button>
            </template>
        </div>
    </div>
</template>
