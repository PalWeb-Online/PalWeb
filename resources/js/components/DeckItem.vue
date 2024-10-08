<script setup>
import {computed, nextTick, onBeforeUnmount, ref} from 'vue';
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
import DeckActions from "./DeckActions.vue";

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
    return props.deck.description.length > 190
        ? props.deck.description.substring(0, 187) + '...'
        : props.deck.description;
});

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

</script>

<template>
    <div :class="['deck-item-wrapper', deck.size]">
        <div class="deck-item" ref="trigger" @click="toggleMenu">
            <div class="deck-metadata">
                <div style="display: flex; gap: 0.8rem; align-items: center">
                    <div class="deck-title">
                        {{ deck.name }}
                    </div>
                    <div class="deck-term-count">
                        ({{ deck.count }})
                    </div>
                </div>
                <div v-if="deck.size === 'l' && deck.description" class="deck-description">
                    {{ description }}
                </div>
            </div>

            <div class="deck-author">
                <div class="deck-author-name">by {{ deck.authorName }}</div>
                <div class="deck-author-avatar">
                    <img alt="Profile Picture" :src="deck.authorAvatar"/>
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
    </div>
</template>
