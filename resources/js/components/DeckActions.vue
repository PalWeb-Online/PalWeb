<script setup>
import {computed} from "vue";
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

const isAuthor = computed(() => {
    return UserStore.user?.id === props.model.author.id;
})

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const deleteDeck = () => {
    if (!confirm('Are you sure you want to delete this Deck?')) return;

    router.delete(route('decks.destroy', props.model.id), {
        onSuccess: () => {
            NotificationStore.addNotification('The Deck has been deleted!');
        }
    });
};

const confirmCopy = (event) => {
    if (!confirm('Are you sure you want to create a copy of this Deck?')) {
        event.preventDefault();
    }
};

const copyLink = (event) => {
    event.preventDefault();

    navigator.clipboard.writeText(route('decks.show', props.model.id)).then(function () {
        alert('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <Link :href="route('decks.show', model.id)">View Deck</Link>

            <template v-if="isAuthor">
                <Link :href="route('deck-master.build', model.id)">Edit Deck</Link>
                <button @click="deleteDeck">Delete Deck</button>
            </template>

            <template v-if="UserStore.isUser">
                <Link :href="route('users.show', model.author.username)">View Creator</Link>
<!--                todo: refactor for Inertia-->
                <form :action="route('decks.copy', model.id)" method="POST" @submit="confirmCopy">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Copy Deck</button>
                </form>
                <button @click="copyLink">Share Link</button>
                <form :action="route('decks.export', model.id)" method="POST">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Export CSV</button>
                </form>
            </template>
        </div>
    </div>
</template>
