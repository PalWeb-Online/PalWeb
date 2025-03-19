<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";
import {useNotificationStore} from "../stores/NotificationStore.js";

const props = defineProps({
    model: Object,
});

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const deleteTerm = () => {
    if (!confirm('Are you sure you want to delete this Term?')) return;

    router.delete(route('terms.destroy', props.model.id), {
        onSuccess: () => {
            NotificationStore.addNotification('The Term has been deleted!');
        }
    });
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <Link :href="route('terms.show', model.slug)">View Term</Link>

            <template v-if="UserStore.isAdmin">
                <Link :href="route('terms.edit', model.id)">Edit Term</Link>
                <button @click="deleteTerm">Delete Term</button>
            </template>
        </div>
    </div>
</template>
