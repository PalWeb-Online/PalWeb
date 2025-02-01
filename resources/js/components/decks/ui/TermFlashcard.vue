<script setup>
import {onMounted, onUnmounted, ref, watch} from 'vue';
import {useTerm} from "../../../composables/Term.js";
import VanillaTilt from "vanilla-tilt";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    active: Boolean,
    flipDefault: Boolean,
    flipDefaultInflections: Boolean,
    showTerm: Boolean,
    showTranslit: Boolean,
});

const flashcard = ref(null);

function handleAudio() {
    const cardElement = flashcard.value.closest('.carousel__slide');
    if (cardElement && cardElement.classList.contains('carousel__slide--clone')) return;

    playAudio();
}

const flipCard = () => {
    const cardElements = document.querySelectorAll(`[data-id="${data.term.id}"]`);
    cardElements.forEach(card => {
        card.classList.toggle('flipped');
    });
};

const handleKeydown = (event) => {
    if (event.key === 'ArrowDown' || event.key === 's') {
        event.preventDefault();
        flipCard();
    }

    if (event.key === 'ArrowUp' || event.key === 'w') {
        event.preventDefault();
        handleAudio();
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

const {data, playAudio} = useTerm(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="term-flashcard-wrapper">
            <img v-if="data.term.audio" class="play" src="/img/audio.svg" alt="play" @click="handleAudio"/>

            <div :class="['term-flashcard', flipDefault ? 'flipped' : '']" :data-id="data.term.id" ref="flashcard"
                 @click="flipCard">
                <div class="term-flashcard-front">
                    <div class="term-flashcard-term">
                        <div>{{ data.term.term }}</div>
                        <div v-show="showTranslit">{{ data.term.translit }}</div>
                    </div>

                    <div v-show="flipDefaultInflections && data.term.inflections.length > 0"
                         class="term-flashcard-inflections">
                        <div v-for="inflection in data.term.inflections" class="term-flashcard-inflection-item">
                            <div>{{ inflection.inflection }}</div>
                            <div v-show="showTranslit">{{ inflection.translit }}</div>
                        </div>
                    </div>
                </div>
                <div class="term-flashcard-back">
                    <div class="term-flashcard-head" v-show="showTerm">
                        <div class="term-flashcard-headword">
                            <div>{{ data.term.term }}</div>
                            <div v-show="showTranslit">({{ data.term.translit }})</div>
                        </div>

                        <div v-show="!flipDefaultInflections && data.term.inflections.length > 0"
                             class="term-flashcard-inflections">
                            <div v-for="inflection in data.term.inflections" class="term-flashcard-inflection-item">
                                <div>{{ inflection.inflection }}</div>
                                <div v-show="showTranslit">({{ inflection.translit }})</div>
                            </div>
                        </div>
                    </div>
                    <div class="term-flashcard-glosses">
                        <div>{{ data.term.category }}.</div>
                        <div v-for="(gloss, index) in data.term.glosses" class="eng">{{ index + 1 }}. {{
                                gloss.gloss
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
