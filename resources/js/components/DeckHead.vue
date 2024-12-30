<script setup>
import {computed, ref} from 'vue';
import ContextActions from "./ContextActions.vue";
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";

const props = defineProps({
    deck: Object,
    isPinned: Boolean,

    // ModelActions
    routes: Object,
    isUser: Boolean,

    // DeckActions
    isAuthor: Boolean,
});

const description = computed(() => {
    return props.deck.description.length > 190
        ? props.deck.description.substring(0, 187) + '...'
        : props.deck.description;
});

</script>

<template>
    <div class="deck-container-head">
        <div class="deck-container-head-title">{{ deck.name }}</div>

        <PinButton v-if="isUser"
                   :route="routes.pin"
                   :isPinned="isPinned"
                   :pinCount="deck.pinCount"
        />
        <PrivacyToggleButton v-if="isAuthor"
                             :route="routes.privacyToggle"
                             :isPrivate="deck.isPrivate"
        />

        <ContextActions
            modelType="deck"
            :routes="routes"
            :isUser="isUser"
            :isAuthor="isAuthor"
        />
    </div>
</template>
