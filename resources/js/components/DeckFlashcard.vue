<script setup>
import {computed, nextTick, onBeforeUnmount, onMounted, ref} from 'vue';
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
import DeckActions from "./DeckActions.vue";
import VanillaTilt from "vanilla-tilt";

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

const description = computed(() => {
    return props.deck.description.length > 320
        ? props.deck.description.substring(0, 317) + '...'
        : props.deck.description;
});

const flipCard = (event) => {
    const flashcard = event.target.closest('.deck-flashcard-wrapper').querySelector('.deck-flashcard');
    if (flashcard) {
        flashcard.classList.toggle('flipped');
    }
};

const isOpen = ref(false);
const trigger = ref(null);
const menu = ref(null);
const clickX = ref(0);
const clickY = ref(0);

const toggleMenu = async (event) => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        clickX.value = event.clientX;
        clickY.value = event.clientY;

        await nextTick(() => {
            const element = menu.value;
            if (element) {
                const rect = element.getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;

                if (clickX.value + rect.width > viewportWidth) {
                    clickX.value = viewportWidth - rect.width - 8;
                }
                if (clickY.value + rect.height > viewportHeight) {
                    clickY.value = viewportHeight - rect.height - 8;
                }
            }
        });

        document.body.style.overflow = 'hidden';
        document.addEventListener('click', handleClickOutside);

    } else {
        document.body.style.overflow = '';
        document.removeEventListener('click', handleClickOutside);
    }
};

const handleClickOutside = (event) => {
    if (!trigger.value.contains(event.target) && !menu.value.contains(event.target)) {
        isOpen.value = false;
        document.body.style.overflow = '';
        document.removeEventListener('click', handleClickOutside);
    }
};

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

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
        <div class="deck-flashcard" ref="trigger" @click="toggleMenu">
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

        <div class="popup-menu" ref="menu" v-if="isOpen"
             :style="{ top: `${clickY}px`, left: `${clickX}px`, position: 'fixed' }">
            <DeckActions
                modelType="deck"
                :routes="routes"
                :isUser="isUser"
                :isAuthor="isAuthor"
            />
        </div>

        <PrivacyToggleButton v-if="isAuthor" :isPrivate="deck.isPrivate"
                             :route="routes.privacyToggle"
                             :imageURL="imageURL"/>
        <PinButton v-if="isUser"
                   :isPinned="isPinned"
                   :pinCount="deck.pinCount"
                   :route="routes.pin"
                   :imageURL="imageURL"
        />

        <img :src="`${imageURL}/flip.svg`" class="flip" @click="flipCard" alt="flip"/>
    </div>
</template>
