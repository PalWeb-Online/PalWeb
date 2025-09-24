<script setup>
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";
import {useNotificationStore} from "../stores/NotificationStore.js";

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const props = defineProps({
    model: Object,
});

const handleToggleMenu = async () => {
    if (!UserStore.hasFetchedDecks) {
        await UserStore.fetchDecks();
    }

    toggleMenu();
}

const toggleTerm = async (deck) => {
    try {
        const response = await axios.post(route('decks.term.toggle', {deck: deck.id, term: props.model.id}));

        if (response.data.isPresent) {
            deck.terms.push(props.model);

        } else {
            deck.terms = deck.terms.filter(term => term.id !== props.model.id);
        }

        NotificationStore.addNotification(response.data.message);

    } catch (error) {
        console.error('Deck Toggle Failed', error);
    }
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <template v-if="UserStore.isUser">
        <div class="popup-menu-wrapper">
            <button class="material-symbols-rounded term-deck-toggle" ref="reference" @click="handleToggleMenu">
                {{ isOpen ? 'folder_open' : 'folder' }}
            </button>

            <Teleport to="body">
                <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
                    <form v-if="UserStore.decks.length > 0">
                        <button v-for="deck in UserStore.decks" :key="deck.id" @click.prevent="toggleTerm(deck)">
                            <span style="font-weight: 700; text-transform: uppercase">
                                [{{ deck.terms.some(term => term.id === model.id) ? '✓' : ' ' }}]
                            </span>
                            {{ deck.name }}
                        </button>
                    </form>
                    <a v-else>
                        No Decks Available.
                    </a>
                </div>
            </Teleport>
        </div>
    </template>
</template>
