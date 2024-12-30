<script setup>
import {onBeforeUnmount, ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

const props = defineProps({
    route: String,
    userDecks: Object,
});


const decks = ref(props.userDecks);
const isOpen = ref(false);
const submenuTrigger = ref(null);
const submenu = ref(null);
const notificationTrigger = ref(null);
const notification = ref(null);

const notifVisible = ref(false);
const notifContent = ref('');

const {floatingStyles: submenuStyles} = useFloating(submenuTrigger, submenu, {
    placement: 'bottom',
    middleware: [offset(8), flip(), shift()],
});

const {floatingStyles: notificationStyles} = useFloating(notificationTrigger, notification, {
    placement: 'left',
    middleware: [offset(8), flip(), shift()],
});

const showDecks = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        document.addEventListener('click', handleClickOutside);
    } else {
        document.removeEventListener('click', handleClickOutside);
    }
};

const handleClickOutside = (event) => {
    if (!submenuTrigger.value.contains(event.target) && !submenu.value.contains(event.target)) {
        isOpen.value = false;
        document.removeEventListener('click', handleClickOutside);
    }
};

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const toggle = async (deck) => {
    try {
        const route = props.route.replace(':deckId', deck.id);
        const response = await axios.post(route);
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
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="submenuTrigger" class="term-deck-toggle"
             :src="`/img/${isOpen ? 'folder-open.svg' : 'folder-closed.svg'}`" @click="showDecks" alt="pin"/>
        <div ref="submenu" v-if="isOpen" :style="submenuStyles" class="popup-menu">
            <form v-if="decks.length > 0" ref="notificationTrigger">
                <button v-for="deck in decks" @click.prevent="toggle(deck)">
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
