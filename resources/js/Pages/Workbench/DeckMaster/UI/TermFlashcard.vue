<script setup>
import {onMounted, onUnmounted, ref, watch} from 'vue';
import VanillaTilt from "vanilla-tilt";
import {useTerm} from "../../../../composables/Term.js";
import AppButton from "../../../../components/AppButton.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    canInteract: {type: Boolean, default: false},
    active: {type: Boolean, default: false},
    audioOnly: {type: Boolean, default: false},
    flipDefault: {type: Boolean, default: false},
    flipDefaultInflections: {type: Boolean, default: false},
    showTerm: {type: Boolean, default: true},
    showTranslit: {type: Boolean, default: false},
});

const emit = defineEmits(['flipped']);

const flashcard = ref(null);
const audioOnly = ref(props.audioOnly);

const flipCard = () => {
    audioOnly.value = false;

    const cardElements = document.querySelectorAll(`[data-id="${props.model.id}"]`);
    cardElements.forEach(card => {
        card.classList.toggle('flipped');
    });

    emit('flipped');
};

defineExpose({flipCard});

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

const {isPlaying, playAudio} = useTerm(props);
</script>

<template>
    <template v-if="!audioOnly">
        <div :class="['term-flashcard', {flipped: flipDefault, disabled: !canInteract}]" :data-id="model.id"
             ref="flashcard"
             @click="flipCard">
            <div class="term-flashcard-front">
                <div class="term-flashcard-term">
                    <div>{{ model.term }}</div>
                    <div v-show="showTranslit">{{ model.translit }}</div>
                    <div>{{ model.category }}</div>
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
        <button v-if="model.audio" @click="playAudio"
                class="audio-button material-symbols-rounded" :class="{'active': isPlaying}">
            music_note
        </button>
    </template>
    <template v-else-if="model.audio">
        <button @click="playAudio"
                class="audio-button material-symbols-rounded" :class="{'active': isPlaying}"
                style="font-size: 6.4rem; padding: 2.4rem; margin-block-end: 3.2rem"
        >
            music_note
        </button>
        <AppButton @click="flipCard" label="Reveal"/>
    </template>
</template>
