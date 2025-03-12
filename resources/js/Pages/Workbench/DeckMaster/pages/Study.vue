<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {useDeckMasterStore} from "../stores/DeckMasterStore.js";
import {Carousel, Pagination, Slide} from "vue3-carousel";
import 'vue3-carousel/dist/carousel.css';
import TermFlashcard from "../ui/TermFlashcard.vue";
import AppDialog from "../../../../components/AppDialog.vue";
import DeckItem from "../../../../components/DeckItem.vue";
import AppButton from "../../../../components/AppButton.vue";

const DeckMasterStore = useDeckMasterStore();

const carouselRef = ref(null);
const currentSlideIndex = ref(0);
const defaultOrder = ref([]);
const isSliding = ref(false);
const flipDefault = ref(false);
const flipDefaultInflections = ref(false);
const showTerm = ref(true);
const showTranslit = ref(false);
const canInteract = ref(false);

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
    DeckMasterStore.data.deckCards = [...defaultOrder.value];
    currentSlideIndex.value = 0;
    toStart();
};

const shuffleCards = () => {
    DeckMasterStore.data.deckCards = [...DeckMasterStore.data.deckCards].sort(() => Math.random() - 0.5);
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
        case 'ArrowDown':
        case 's':
            event.preventDefault();
            canInteract.value = !canInteract.value;
            break;
        case ' ':
            event.preventDefault();
            flipDefault.value = !flipDefault.value;
            break;
    }
};

onMounted(async () => {
    defaultOrder.value = [...DeckMasterStore.data.deckCards];
    window.addEventListener('keydown', handleGlobalKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleGlobalKeydown);
});
</script>

<template>
    <div class="app-nav-interact">
        <img src="/img/finger-back.svg" @click="DeckMasterStore.toSelect" alt="Back"/>
    </div>
    <div class="dm-study-options">
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
    <DeckItem :model="DeckMasterStore.data.stagedDeck"/>

    <Carousel
        v-if="!DeckMasterStore.data.isLoading"
        :items-to-show="1"
        :wrap-around="true"
        ref="carouselRef"
        @slide-start="handleSlideStart"
        @slide-end="handleSlideEnd"
    >
        <template #slides>
            <Slide v-for="(term, index) in DeckMasterStore.data.deckCards" :key="term.id">
                <TermFlashcard
                    :model="term"
                    :active="index === currentSlideIndex && !isSliding"
                    :flipDefault="flipDefault"
                    :showTerm="showTerm"
                    :showTranslit="showTranslit"
                    :flipDefaultInflections="flipDefaultInflections"
                    :canInteract="canInteract"
                />
            </Slide>
        </template>
        <template #addons>
            <AppButton @click="canInteract = !canInteract" :active="canInteract" label="See Term"/>
            <Pagination/>
            <div class="carousel-index">{{ currentSlideIndex + 1 }} out of {{
                    DeckMasterStore.data.deckCards.length
                }}
            </div>
        </template>
    </Carousel>
    <div v-else class="app-loading">
        <img src="/img/wait.svg" alt="Loading"/>
    </div>
</template>
