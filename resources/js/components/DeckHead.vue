<script setup>
import {computed, ref} from 'vue';
import ContextActions from "./ContextActions.vue";
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";

const props = defineProps({
    deck: Object,
    imageURL: String,
    isPinned: Boolean,

    // ContextActions
    modelType: String,

    // ModelActions
    routes: Object,
    isUser: Boolean,

    // DeckActions
    isAuthor: Boolean,
});

let pinCount = ref(props.deck.pinCount);
const updateCount = (count) => {
    pinCount.value = count;
};

const description = computed(() => {
    return props.deck.description.length > 190
        ? props.deck.description.substring(0, 187) + '...'
        : props.deck.description;
});

</script>

<template>
    <div class="deck-container-head">
        <div class="deck-container-head-title">{{ deck.name }}</div>

        <PinButton v-if="isUser" :isPinned="isPinned" :route="routes.pin" :imageURL="imageURL" @updateCount="updateCount" />
        <div v-if="pinCount > 1" class="pin-counter">
            <img :src="`${imageURL}/heart.svg`" alt="heart"/>
            <div>{{ pinCount }}</div>
        </div>

        <PrivacyToggleButton v-if="isAuthor" :isPrivate="deck.isPrivate" :route="routes.privacyToggle" :imageURL="imageURL" />

        <ContextActions
            :imageURL="imageURL"
            :modelType="modelType"
            :routes="routes"
            :isUser="isUser"
            :isAuthor="isAuthor"
        />
    </div>
</template>
