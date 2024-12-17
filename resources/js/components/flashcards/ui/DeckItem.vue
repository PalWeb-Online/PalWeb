<script setup>
import {computed, onMounted, ref} from 'vue';
import VanillaTilt from "vanilla-tilt";

const props = defineProps({
    deck: Object,
    active: false,
});

const description = computed(() => {
    return props.deck.description.length > 320
        ? props.deck.description.substring(0, 317) + '...'
        : props.deck.description;
});

const trigger = ref(null);

onMounted(() => {
    VanillaTilt.init(trigger.value, {
        max: 10,
        speed: 400,
        scale: 1,
    });
});

</script>

<template>
    <div :class="['deck-flashcard-wrapper', { active: active }]">
        <div :class="['deck-flashcard', {flipped: active }]" ref="trigger">
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
    </div>
</template>
