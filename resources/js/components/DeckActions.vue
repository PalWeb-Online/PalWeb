<script setup>
import {computed} from "vue";
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3'
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const isAuthor = computed(() => {
    return props.model.author.id === UserStore.user.id;
})

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmDelete = (event) => {
    if (!confirm(`Are you sure you want to delete this Deck?`)) {
        event.preventDefault();
    }
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

const { toggleMenu, floatingStyles, isOpen, reference, floating } = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <Link :href="route('decks.show', model.id)">View Deck</Link>

            <template v-if="isAuthor">
                <Link :href="route('deck-master.edit', model.id)">Edit Deck</Link>

                <form :action="route('decks.destroy', model.id)" method="POST" @submit="confirmDelete">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Delete Deck</button>
                </form>
            </template>

            <template v-if="UserStore.isUser">
                <a :href="route('users.show', model.author.username)">View Creator</a>
                <form :action="route('decks.copy', model.id)" method="POST" @submit="confirmCopy">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Copy Deck</button>
                </form>
                <a href="#" @click="copyLink">Share Link</a>
                <form :action="route('decks.export', model.id)" method="POST">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Export CSV</button>
                </form>
            </template>
        </div>
    </div>
</template>
