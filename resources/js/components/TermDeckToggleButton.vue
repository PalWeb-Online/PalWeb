<script setup>
import {ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const notificationTrigger = ref(null);
const notification = ref(null);

const notifVisible = ref(false);
const notifContent = ref('');

const {floatingStyles: notificationStyles} = useFloating(notificationTrigger, notification, {
    placement: 'left',
    middleware: [offset(8), flip(), shift()],
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

        notifContent.value = response.data.message;
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);
    } catch (error) {
        console.error('Deck Toggle Failed', error);
        notifContent.value = 'An error occurred.';
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);
    }
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <template v-if="UserStore.isUser">
        <div class="popup-menu-wrapper">
            <img ref="reference" class="term-deck-toggle"
                 :src="`/img/${isOpen ? 'folder-open.svg' : 'folder-closed.svg'}`" @click="handleToggleMenu" alt="pin"/>

            <Teleport to="body">
                <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
                    <form v-if="UserStore.decks.length > 0" ref="notificationTrigger">
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
                    <Transition name="notification">
                        <div ref="notification" :style="notificationStyles" v-if="notifVisible" class="notification">
                            {{ notifContent }}
                        </div>
                    </Transition>
                </div>
            </Teleport>
        </div>
    </template>
</template>
