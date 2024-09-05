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
    return props.deck.description.length > 320
        ? props.deck.description.substring(0, 317) + '...'
        : props.deck.description;
});

const flip = (event) => {
    const flashcard = event.target.closest('.deck-flashcard');
    if (flashcard) {
        flashcard.classList.toggle('flipped');
    }
};

</script>

<template>
    <div class="deck-flashcard-wrapper">
        <div class="deck-flashcard" :href="routes.view">
            <div class="deck-flashcard-front">
                <div class="deck-flashcard-front-head">
                    <div class="deck-title">{{ deck.name }}</div>
                    <div class="deck-author" style="align-self: flex-end">
                        <PrivacyToggleButton v-if="isAuthor" :isPrivate="deck.isPrivate" :route="routes.privacyToggle"
                                             :imageURL="imageURL"/>

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

            <PinButton v-if="isUser" :isPinned="isPinned" :route="routes.pin" :imageURL="imageURL"
                       @updateCount="updateCount"/>
            <div v-if="pinCount > 1" class="pin-counter">
                <img :src="`${imageURL}/heart.svg`" alt="heart"/>
                <div>{{ pinCount }}</div>
            </div>
            <img :src="`${imageURL}/flip.svg`" class="flip" @click="flip" alt="flip"/>
        </div>

        <ContextActions
            :imageURL="imageURL"
            :modelType="modelType"
            :routes="routes"
            :isUser="isUser"
            :isAuthor="isAuthor"
        />
    </div>
</template>
