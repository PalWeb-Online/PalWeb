<script setup>
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";
import {useDeck} from "../composables/Deck.js";

const UserStore = useUserStore();
const {toggleTerm} = useDeck();
const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();

const props = defineProps({
    model: Object,
});

const handleToggleMenu = async () => {
    if (!UserStore.hasFetchedDecks) {
        await UserStore.fetchDecks();
    }

    toggleMenu();
}
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
                        <button v-for="deck in UserStore.decks" :key="deck.id" @click.prevent="toggleTerm(deck, model)">
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
