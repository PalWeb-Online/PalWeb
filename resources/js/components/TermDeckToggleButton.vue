<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const decks = ref([]);

const notificationTrigger = ref(null);
const notification = ref(null);

const notifVisible = ref(false);
const notifContent = ref('');

const {floatingStyles: notificationStyles} = useFloating(notificationTrigger, notification, {
    placement: 'left',
    middleware: [offset(8), flip(), shift()],
});

const toggleTerm = async (deck) => {
    try {
        const response = await axios.post(route('decks.term.toggle', {deck: deck.id, term: props.model.id}));
        deck.isPresent = response.data.isPresent;
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

const fetchDecks = async () => {
    try {
        const response = await axios.get(route('users.get.decks', props.model.id));
        decks.value = response.data.decks;

    } catch (error) {
        console.error('Deck Fetch Failed', error);
    }
}

onMounted(fetchDecks);

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <template v-if="UserStore.isUser">
        <div class="popup-menu-wrapper">
            <img ref="reference" class="term-deck-toggle"
                 :src="`/img/${isOpen ? 'folder-open.svg' : 'folder-closed.svg'}`" @click="toggleMenu" alt="pin"/>

            <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
                <form v-if="decks.length > 0" ref="notificationTrigger">
                    <button v-for="deck in decks" @click.prevent="toggleTerm(deck)">
                    <span style="font-weight: 700; text-transform: uppercase">
                        [{{ deck.isPresent ? '✓' : ' ' }}]
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
        </div>
    </template>
</template>
