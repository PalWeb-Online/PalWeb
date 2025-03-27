<script setup>
import {onMounted, onUnmounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import {Carousel, Pagination, Slide} from "vue3-carousel";
import 'vue3-carousel/dist/carousel.css';
import TermFlashcard from "../ui/TermFlashcard.vue";
import DeckItem from "../../../../components/DeckItem.vue";
import AppButton from "../../../../components/AppButton.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";

const props = defineProps({
    deck: Object,
    terms: Array,
});

const cards = reactive([]);

const carouselRef = ref(null);
const currentSlideIndex = ref(0);
const isSliding = ref(false);
const flipDefault = ref(false);
const flipDefaultInflections = ref(false);
const showTerm = ref(true);
const showTranslit = ref(false);

const handleSlideStart = () => {
    isSliding.value = true;
};

const handleSlideEnd = ({currentSlideIndex: newIndex}) => {
    currentSlideIndex.value = newIndex;
    isSliding.value = false;
};

const toStart = () => {
    carouselRef.value?.slideTo(0);

    const flashcards = document.querySelectorAll('.term-flashcard');
    flashcards.forEach(card => {
        if (flipDefault.value) {
            card.classList.add('flipped');
        } else {
            card.classList.remove('flipped');
        }
    });
}

const resetCards = () => {
    cards.splice(0, cards.length, ...props.terms);
    currentSlideIndex.value = 0;
    toStart();
};

const shuffleCards = () => {
    const shuffled = [...cards].sort(() => Math.random() - 0.5);
    cards.splice(0, cards.length, ...shuffled);
    currentSlideIndex.value = 0;
    toStart();
};

const handleGlobalKeydown = (event) => {
    if (!carouselRef.value || isSliding.value) return;

    switch (event.key) {
        case 'ArrowRight':
        case 'd':
            carouselRef.value?.next();
            break;
        case 'ArrowLeft':
        case 'a':
            carouselRef.value?.prev();
            break;
        case ' ':
            event.preventDefault();
            flipDefault.value = !flipDefault.value;
            break;
    }
};

onMounted(async () => {
    cards.splice(0, cards.length, ...props.terms);
    window.addEventListener('keydown', handleGlobalKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleGlobalKeydown);
});
</script>

<template>
    <div class="app-nav-interact">
        <img src="/img/finger-back.svg" @click="router.get(route('deck-master.index', {mode: 'study'}))" alt="Back"/>
    </div>
    <div class="dm-study-options">
        <div class="window-head">
            <div>Options</div>
            <PopupWindow title="Deck Master">
                <template #trigger>
                    <div class="material-symbols-rounded">help</div>
                </template>
                <template #content>
                    <div>Settings</div>
                    <ul>
                        <li><b>Reset</b> — Restores the Cards in the Deck to their original order.</li>
                        <li><b>Shuffle</b> — Shuffles the Cards in the Deck.</li>
                        <li><b>Default Side Toggle</b> — Toggles the default face of Cards in the Deck. Cards that have
                            already been flipped will be unaffected.
                        </li>
                        <li><b>Show Inflections Toggle</b> — Toggles whether Inflections are shown on the Term side or on
                            the Gloss side of the Card.
                        </li>
                        <li><b>Show Transcription</b> — Show the default transcription of the Term & its Inflections.</li>
                        <li><b>Show Term</b> — Show the Term on the Gloss side of the Card.</li>
                    </ul>

                    <div>Keyboard Controls</div>
                    <ul>
                        <li><b>Left</b> / <b>A</b> — Previous Card</li>
                        <li><b>Right</b> / <b>D</b> — Next Card</li>
                        <li><b>Down</b> / <b>S</b> — Flip Card</li>
                        <li><b>Space</b> — Toggle Default Side</li>
                    </ul>
                </template>
            </PopupWindow>
        </div>
        <div>
            <AppButton @click="resetCards" label="Reset"/>
            <AppButton @click="shuffleCards" label="Shuffle"/>
            <AppButton @click="flipDefault = !flipDefault" :label="`${flipDefault ? 'Gloss' : 'Term'} First`"/>
            <AppButton @click="flipDefaultInflections = !flipDefaultInflections" :label="`Inflections: ${ flipDefaultInflections ? 'Front' : 'Back' }`"/>
            <label class="checkbox">
                <input type="checkbox" value=1 v-model="showTranslit">
                <span>Show Transcription</span>
            </label>
            <label class="checkbox">
                <input type="checkbox" value=1 v-model="showTerm">
                <span>Show Term (Back Side)</span>
            </label>
        </div>
    </div>
    <DeckItem :model="deck"/>

    <Carousel
        :items-to-show="1"
        :wrap-around="true"
        ref="carouselRef"
        @slide-start="handleSlideStart"
        @slide-end="handleSlideEnd"
    >
        <template #slides>
            <Slide v-for="(term, index) in cards" :key="term.id">
                <TermFlashcard
                    :model="term"
                    :active="index === currentSlideIndex && !isSliding"
                    :flipDefault="flipDefault"
                    :showTerm="showTerm"
                    :showTranslit="showTranslit"
                    :flipDefaultInflections="flipDefaultInflections"
                />
            </Slide>
        </template>
        <template #addons>
            <Pagination/>
            <div class="carousel-index">{{ currentSlideIndex + 1 }} out of {{ cards.length }}</div>
        </template>
    </Carousel>
</template>
