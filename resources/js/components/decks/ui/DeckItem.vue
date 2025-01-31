<script setup>
import {computed, onMounted, ref} from 'vue';
import VanillaTilt from "vanilla-tilt";
import PinButton from "./PinButton.vue";
import AppTooltip from "../../AppTooltip.vue";

const props = defineProps({
    context: {type: String, default: null},
    id: {type: [String, Number], default: null},
    deck: Object,
    isActive: Boolean,
});

const emit = defineEmits(['flip']);

const id = computed(() => props.id || props.deck?.id);
const tooltip = ref(null);
const flashcard = ref(null);

const handleFlip = () => {
    emit('flip', id.value);
};

const disabled = computed(() => {
    return props.context === 'viewer' && props.deck.count === 0;
});

const description = computed(() => {
    return props.deck?.description?.length > 320
        ? props.deck.description.substring(0, 317) + '...'
        : props.deck?.description;
});

onMounted(() => {
    VanillaTilt.init(flashcard.value, {
        max: 10,
        speed: 400,
        scale: 1,
    });
});
</script>

<template>
    <div :class="['deck-flashcard-wrapper', { active: isActive }]"
         @mousemove="disabled && tooltip.showTooltip('This Deck is empty.', $event);"
         @mouseleave="disabled && tooltip.hideTooltip()"
    >
        <div :class="['deck-flashcard', {flipped: isActive }, { disabled: disabled}]" ref="flashcard"
             @click="handleFlip">
            <div class="deck-flashcard-front">
                <slot name="front">
                    <div class="deck-flashcard-front-head">
                        <div class="item-title">{{ deck.name }}</div>
                        <div class="deck-author" style="align-self: flex-end">
                            <div class="deck-author-name">by {{ deck.author.name }}</div>
                            <img class="deck-author-avatar" alt="Profile Picture" :src="`/img/avatars/${deck.author.avatar}`"/>
                            <!--                        <div class="deck-author-name">by Deleted User</div>-->
                        </div>
                    </div>
                    <div class="deck-flashcard-front-body">
                        <div v-if="deck.description" class="item-description">{{ description }}</div>
                    </div>
                </slot>
            </div>
            <div class="deck-flashcard-back">
                <slot name="back">
                    <div class="deck-flashcard-back-head">{{ deck.terms.length }} terms</div>
                    <div class="deck-flashcard-back-body">
                        <div v-for="term in deck.terms.slice(0, 16)">{{ term.term }}</div>
                        <div v-if="deck.terms.length > 16" style="grid-column: span 2">...</div>
                    </div>
                </slot>
            </div>
        </div>

        <PinButton :id="deck.id" model="deck"
                   :isPinned="deck.isPinned"
        />
    </div>

    <AppTooltip ref="tooltip"/>
</template>
