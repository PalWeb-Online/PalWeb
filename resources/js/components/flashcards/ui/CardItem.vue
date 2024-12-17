<script setup>
import {onMounted, onUnmounted, ref, watch} from 'vue';
import {Howl} from 'howler';
import VanillaTilt from "vanilla-tilt";

const props = defineProps({
    term: Object,
    isActive: Boolean,
    flipDefault: Boolean,
    flipDefaultInflections: Boolean,
    showTerm: Boolean,
    showTranslit: Boolean,
});

const audio = ref(null);
const card = ref(null);

function playAudio() {
    const cardElement = card.value.closest('.carousel__slide');
    if (cardElement && cardElement.classList.contains('carousel__slide--clone')) return;

    if (audio.value) audio.value.play();
}

const flipCard = () => {
    const cardElements = document.querySelectorAll(`[data-id="${props.term.id}"]`);
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
        playAudio();
    }
};

onMounted(() => {
    if (props.term.audio) {
        audio.value = new Howl({
            src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audio/${props.term.audio}.mp3`],
        });
    }

    VanillaTilt.init(card.value, {
        max: 10,
        speed: 400,
        scale: 1,
    });

    if (props.isActive) {
        window.addEventListener('keydown', handleKeydown);
    }
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

watch(() => props.isActive, (newVal) => {
    if (newVal) {
        window.addEventListener('keydown', handleKeydown);

    } else {
        window.removeEventListener('keydown', handleKeydown);
    }
});
</script>

<template>
    <div class="term-flashcard-wrapper">
        <img v-if="term.audio" class="play" src="/img/audio.svg" alt="play" @click="playAudio"/>

        <div :class="['term-flashcard', flipDefault ? 'flipped' : '']" :data-id="term.id" ref="card" @click="flipCard">
            <div class="term-flashcard-front">
                <div class="term-flashcard-term">
                    <div>{{ term.term }}</div>
                    <div v-show="showTranslit">{{ term.translit }}</div>
                </div>

                <div v-show="flipDefaultInflections && term.inflections.length > 0"
                     class="term-flashcard-inflections">
                    <div v-for="inflection in term.inflections" class="term-flashcard-inflection-item">
                        <div>{{ inflection.inflection }}</div>
                        <div v-show="showTranslit">{{ inflection.translit }}</div>
                    </div>
                </div>
            </div>
            <div class="term-flashcard-back">
                <div class="term-flashcard-head" v-show="showTerm">
                    <div class="term-flashcard-headword">
                        <div>{{ term.term }}</div>
                        <div v-show="showTranslit">({{ term.translit }})</div>
                    </div>

                    <div v-show="!flipDefaultInflections && term.inflections.length > 0"
                         class="term-flashcard-inflections">
                        <div v-for="inflection in term.inflections" class="term-flashcard-inflection-item">
                            <div>{{ inflection.inflection }}</div>
                            <div v-show="showTranslit">({{ inflection.translit }})</div>
                        </div>
                    </div>
                </div>
                <div class="term-flashcard-glosses">
                    <div>{{ term.category }}.</div>
                    <div v-for="(gloss, index) in term.glosses" class="eng">{{ index + 1 }}. {{ gloss.gloss }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
