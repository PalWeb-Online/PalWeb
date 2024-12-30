<script setup>
import {computed, onMounted, ref} from 'vue';
import VanillaTilt from "vanilla-tilt";

const props = defineProps({
    id: { type: [String, Number], default: null },
    deck: Object,
    isActive: Boolean,
});

const emit = defineEmits(['flip']);

const id = computed(() => props.id || props.deck?.id);
const trigger = ref(null);

const handleFlip = () => {
    emit('flip', id.value);
};

const description = computed(() => {
    return props.deck?.description?.length > 320
        ? props.deck.description.substring(0, 317) + '...'
        : props.deck?.description;
});

onMounted(() => {
    VanillaTilt.init(trigger.value, {
        max: 10,
        speed: 400,
        scale: 1,
    });
});
</script>

<template>
    <div :class="['deck-flashcard-wrapper', { active: isActive }]">
        <div :class="['deck-flashcard', {flipped: isActive }]" ref="trigger" @click="handleFlip">
            <div class="deck-flashcard-front">
                <slot name="front">
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
                </slot>
            </div>
            <div class="deck-flashcard-back">
                <slot name="back">
                    <div class="deck-flashcard-back-head">{{ deck.count }} terms</div>
                    <div class="deck-flashcard-back-body">
                        <div v-for="term in deck.terms.slice(0, 16)">{{ term }}</div>
                        <div v-if="deck.terms.length > 16" style="grid-column: span 2">...</div>
                    </div>
                </slot>
            </div>
        </div>
    </div>
</template>
