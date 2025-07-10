<script setup>
import {computed} from "vue";
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    model: Object,
    icon: {type: String, default: 'symbol'},
});

const UserStore = useUserStore();

const isAuthor = computed(() => {
    return UserStore.user?.id === props.model.author.id;
})

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const deleteDeck = () => {
    if (!confirm('Are you sure you want to delete this Deck?')) return;

    router.delete(route('decks.destroy', props.model.id));
};

const copyDeck = (event) => {
    if (!confirm('Are you sure you want to create a copy of this Deck?')) return

    router.post(route('decks.copy', props.model.id))
};

const copyLink = (event) => {
    event.preventDefault();

    navigator.clipboard.writeText(route('decks.show', props.model.id)).then(function () {
        alert('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
};
</script>

<template>
    <ContextActions :icon="icon" v-slot="{ closeMenu }">
        <Link :href="route('decks.show', model.id)" role="menuitem" tabindex="-1">
            View Deck
        </Link>

        <template v-if="isAuthor">
            <Link :href="route('deck-master.build', model.id)" role="menuitem" tabindex="-1">
                Edit Deck
            </Link>
            <button @click="deleteDeck" role="menuitem" tabindex="-1">
                Delete Deck
            </button>
        </template>

        <template v-if="UserStore.isUser">
            <Link :href="route('users.show', model.author.username)" role="menuitem" tabindex="-1">
                View Creator
            </Link>
            <button @click="copyDeck" role="menuitem" tabindex="-1">
                Copy Deck
            </button>
            <button @click="copyLink" role="menuitem" tabindex="-1">
                Share Link
            </button>
            <form :action="route('decks.export', model.id)" method="POST">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" role="menuitem" tabindex="-1">
                    Export CSV
                </button>
            </form>
        </template>
    </ContextActions>
</template>
