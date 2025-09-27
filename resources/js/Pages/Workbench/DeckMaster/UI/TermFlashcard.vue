<script setup>
import {onMounted, onUnmounted, ref, watch} from 'vue';
import VanillaTilt from "vanilla-tilt";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    active: {type: Boolean, default: false},
    flipDefault: {type: Boolean, default: false},
    flipDefaultInflections: {type: Boolean, default: false},
    showTerm: {type: Boolean, default: true},
    showTranslit: {type: Boolean, default: false},
});

const flashcard = ref(null);

const flipCard = () => {
    const cardElements = document.querySelectorAll(`[data-id="${props.model.id}"]`);
    cardElements.forEach(card => {
        card.classList.toggle('flipped');
    });
};

const handleKeydown = (event) => {
    if (event.key === 'ArrowUp' || event.key === 'w' || event.key === 'ArrowDown' || event.key === 's') {
        event.preventDefault();
        flipCard();
    }
};

onMounted(() => {
    VanillaTilt.init(flashcard.value, {
        max: 10,
        speed: 400,
        scale: 1,
    });

    if (props.active) {
        window.addEventListener('keydown', handleKeydown);
    }
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

watch(() => props.active, (newVal) => {
    if (newVal) {
        window.addEventListener('keydown', handleKeydown);

    } else {
        window.removeEventListener('keydown', handleKeydown);
    }
});
</script>

<template>
    <div :class="['term-flashcard', flipDefault ? 'flipped' : '']" :data-id="model.id" ref="flashcard"
         @click="flipCard">
        <div class="term-flashcard-front">
            <div class="term-flashcard-term">
                <div>{{ model.term }}</div>
                <div v-show="showTranslit">{{ model.translit }}</div>
            </div>
            <div v-show="flipDefaultInflections && model.inflections.length > 0"
                 class="term-flashcard-inflections">
                <div v-for="inflection in model.inflections" class="term-flashcard-inflection-item">
                    <div>{{ inflection.inflection }}</div>
                    <div v-show="showTranslit">{{ inflection.translit }}</div>
                </div>
            </div>
        </div>
        <div class="term-flashcard-back">
            <div class="term-flashcard-head" v-show="showTerm">
                <div class="term-flashcard-headword">
                    <div>{{ model.term }}</div>
                    <div v-show="showTranslit">({{ model.translit }})</div>
                </div>
                <div v-show="!flipDefaultInflections && model.inflections.length > 0"
                     class="term-flashcard-inflections">
                    <div v-for="inflection in model.inflections" class="term-flashcard-inflection-item">
                        <div>{{ inflection.inflection }}</div>
                        <div v-show="showTranslit">({{ inflection.translit }})</div>
                    </div>
                </div>
            </div>
            <div class="term-flashcard-glosses">
                <div class="term-flashcard-category">{{ model.category }}.</div>
                <div v-for="(gloss, index) in model.glosses" class="eng">{{ index + 1 }}. {{
                        gloss.gloss
                    }}
                </div>
            </div>
        </div>
    </div>
</template>
