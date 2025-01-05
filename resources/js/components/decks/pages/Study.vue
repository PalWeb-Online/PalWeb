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

const isContentVisible = ref(false);
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

const resetCards = () => {
    DeckStore.resetCards();
    toStart();
};

const shuffleCards = () => {
    DeckStore.shuffleCards();
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
    isContentVisible.value = await DeckStore.fetchCards(DeckStore.data.stagedDeck.id);
    window.addEventListener('keydown', handleGlobalKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleGlobalKeydown);
});
</script>

<template>
    <div class="cv-box">
        <div>Options</div>
        <div>
            <button @click="resetCards">Reset</button>
            <button @click="shuffleCards">Shuffle</button>
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

        <AppDialog title="Options" size="large">
            <template #trigger>
                <img alt="Info" src="/img/idea.svg"/>
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
        </AppDialog>
    </div>

    <Carousel
        v-if="isContentVisible"
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
    <div v-else class="app-loading">
        <img src="/img/wait.svg" alt="Loading"/>
    </div>
</template>
