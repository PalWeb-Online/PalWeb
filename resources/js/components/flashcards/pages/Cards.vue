<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import {Carousel, Pagination, Slide} from "vue3-carousel";
import 'vue3-carousel/dist/carousel.css';
import CardItem from "../ui/CardItem.vue";
import AppDialog from "../../AppDialog.vue";

const StateStore = useStateStore();
const DeckStore = useDeckStore();

const carouselRef = ref(null);
const isSliding = ref(false);
const flipDefault = ref(false);
const flipDefaultInflections = ref(false);
const showTerm = ref(true);
const showTranslit = ref(false);

const handleSlideStart = () => {
    isSliding.value = true;
};

const handleSlideEnd = ({currentSlideIndex: newIndex}) => {
    DeckStore.currentSlideIndex = newIndex;
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

const reset = () => {
    DeckStore.reset();
    toStart();
};

const shuffle = () => {
    DeckStore.shuffle();
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
    await DeckStore.fetchCards(DeckStore.data.deck.id);
    window.addEventListener('keydown', handleGlobalKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleGlobalKeydown);
});
</script>

<template>
    <div class="flashcard-portal-box">
        <div>Options</div>
        <div>
            <button @click="reset">Reset</button>
            <button @click="shuffle">Shuffle</button>
            <button @click="flipDefault = !flipDefault">Default Side: {{ flipDefault ? 'Gloss' : 'Term' }}</button>
            <button @click="flipDefaultInflections = !flipDefaultInflections">Show Inflections:
                {{ flipDefaultInflections ? 'Front' : 'Back' }}
            </button>
            <label class="checkbox">
                <input type="checkbox" value=1 v-model="showTranslit">
                <span>Show Transcription</span>
            </label>
            <label class="checkbox">
                <input type="checkbox" value=1 v-model="showTerm">
                <span>Show Term (Back Side)</span>
            </label>
        </div>

        <AppDialog title="Controls" size="large">
            <template #trigger>
                <img alt="Info" src="/img/idea.svg"/>
            </template>
            <template #content>
                <div><b>Left</b> / <b>A</b> Previous Card</div>
                <div><b>Right</b> / <b>D</b> Next Card</div>
                <div><b>Down</b> / <b>S</b> Flip Card</div>
                <div><b>Up</b> / <b>W</b> Play Audio</div>
                <div><b>Space</b> Toggle Default Side</div>
            </template>
        </AppDialog>
    </div>

    <Carousel
        :items-to-show="1"
        :wrap-around="true"
        ref="carouselRef"
        @slide-start="handleSlideStart"
        @slide-end="handleSlideEnd"
    >
        <template #slides>
            <Slide v-for="(term, index) in DeckStore.data.cards" :key="term.id">
                <CardItem
                    :term="term"
                    :isActive="index === DeckStore.currentSlideIndex && !isSliding"
                    :flipDefault="flipDefault"
                    :showTerm="showTerm"
                    :showTranslit="showTranslit"
                    :flipDefaultInflections="flipDefaultInflections"
                />
            </Slide>
        </template>
        <template #addons>
            <Pagination/>
            <div class="carousel-index">{{ DeckStore.currentSlideIndex + 1 }} out of {{
                    DeckStore.data.cards.length
                }}
            </div>
        </template>
    </Carousel>
</template>
