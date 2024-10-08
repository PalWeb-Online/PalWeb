<script setup>
import {Howl} from 'howler';
import {onMounted, onUnmounted, ref, watch} from 'vue';
import ContextActions from "./ContextActions.vue";
import VanillaTilt from "vanilla-tilt";

const props = defineProps({
    term: Object,
    imageURL: String,
    isUser: Boolean,
    isAdmin: Boolean,
    isActive: Boolean,
    flipDefault: Boolean,
    flipDefaultInflections: Boolean,
    showTerm: Boolean,
    showTranslit: Boolean,
});

const audio = ref(null);
const trigger = ref(null);

function playAudio() {
    const cardElement = trigger.value.closest('.carousel__slide');
    if (cardElement && cardElement.classList.contains('carousel__slide--clone')) {
        return;
    }

    if (audio.value) {
        audio.value.play();
    }
}

const flipCard = () => {
    const cardElements = document.querySelectorAll(`[data-id="${props.term.id}"]`);  // Find both original and cloned cards
    cardElements.forEach(card => {
        card.classList.toggle('flipped');
    });
};

onMounted(() => {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/${props.term.file}.mp3`],
    });

    VanillaTilt.init(trigger.value, {
        max: 10,
        speed: 400,
        scale: 1,
        glare: true,
        "max-glare": 0.1,
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

</script>

<template>
    <div class="term-flashcard-wrapper">
        <img class="play" :src="`${imageURL}/play.svg`" alt="play" @click="playAudio"/>

        <div :class="['term-flashcard', flipDefault ? 'flipped' : '']" :data-id="term.id" ref="trigger" @click="flipCard">
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
        <ContextActions
            modelType="term"
            :imageURL="imageURL"
            :routes="term.routes"
            :isUser="isUser"
            :isAdmin="isAdmin"
        />
    </div>
</template>
