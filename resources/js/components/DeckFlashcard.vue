<script setup>
import {computed, nextTick, onBeforeUnmount, onMounted, ref} from 'vue';
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
import DeckActions from "./DeckActions.vue";
import VanillaTilt from "vanilla-tilt";
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    deck: Object,
    imageURL: String,
    isPinned: Boolean,

    // ModelActions
    routes: Object,
    isUser: Boolean,

    // DeckActions
    isAuthor: Boolean,
});

const description = computed(() => {
    return props.deck.description.length > 320
        ? props.deck.description.substring(0, 317) + '...'
        : props.deck.description;
});

const flipCard = (event) => {
    const card = event.target.closest('.deck-flashcard-wrapper').querySelector('.deck-flashcard');
    if (card) {
        card.classList.toggle('flipped');
    }
};

const trigger = ref(null);

onMounted(() => {
    VanillaTilt.init(trigger.value, {
        max: 10,
        speed: 400,
        scale: 1,
        glare: true,
        "max-glare": 0.5,
    });
});

</script>

<template>
    <div class="deck-flashcard-wrapper">
        <div class="deck-flashcard" ref="trigger" @click="flipCard">
            <div class="deck-flashcard-front">
                <div class="deck-flashcard-front-head">
                    <div class="deck-title">{{ deck.name }}</div>
                    <div class="deck-author" style="align-self: flex-end">
                        <div class="deck-author-name">by {{ deck.authorName }}</div>
                        <img class="deck-author-avatar" alt="Profile Picture" :src="deck.authorAvatar"/>
                        <!--                        <div class="deck-author-name">by Deleted User</div>-->
                    </div>
                </div>
                <div class="deck-flashcard-front-body">
                    <div v-if="deck.description" class="deck-description">{{ description }}</div>
                </div>
            </div>
            <div class="deck-flashcard-back">
                <div class="deck-flashcard-back-head">{{ deck.count }} terms</div>
                <div class="deck-flashcard-back-body">
                    <div v-for="term in deck.terms.slice(0, 16)">{{ term }}</div>
                    <div v-if="deck.terms.length > 16" style="grid-column: span 2">...</div>
                </div>
            </div>
        </div>

        <PinButton v-if="isUser"
                   :route="routes.pin"
                   :imageURL="imageURL"
                   :isPinned="isPinned"
                   :pinCount="deck.pinCount"
        />
        <PrivacyToggleButton v-if="isAuthor"
                             :route="routes.privacyToggle"
                             :imageURL="imageURL"
                             :isPrivate="deck.isPrivate"
        />

        <ContextActions
            modelType="deck"
            :imageURL="imageURL"
            :routes="routes"
            :isUser="isUser"
            :isAuthor="isAuthor"
        />
    </div>
</template>
