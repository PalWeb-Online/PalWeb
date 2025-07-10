<script setup>
import {onMounted, onUnmounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Carousel, Pagination, Slide} from "vue3-carousel";
import 'vue3-carousel/dist/carousel.css';
import TermFlashcard from "../ui/TermFlashcard.vue";
import DeckItem from "../../../../components/DeckItem.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import WindowSection from "../../../../components/WindowSection.vue";
import TermItem from "../../../../components/TermItem.vue";

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
    <div class="window-container">
        <div class="window-header">
            <Link :href="route('deck-master.index', {mode: 'study'})" class="material-symbols-rounded">
                arrow_back
            </Link>
            <div class="window-header-url">www.palweb.app/workbench/deck-master/study/{deck}</div>
            <button class="material-symbols-rounded" @click="shuffleCards">shuffle</button>
            <button class="material-symbols-rounded" @click="resetCards">undo</button>
        </div>
        <div class="window-section-head">
            <h1>Options</h1>
            <PopupWindow title="Deck Master">
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
            </PopupWindow>
        </div>
        <div
            style="padding: 1.6rem; display: flex; flex-flow: row wrap; justify-content: space-between; align-items: center; gap: 1.6rem;">
            <div class="field-compound-toggle-wrapper">
                <div class="field-toggle-title">initial face</div>
                <div class="field-toggle-wrapper">
                    <div :class="!flipDefault ? 'active' : ''">term</div>
                    <button class="field-toggle" :class="{ active: flipDefault }"
                            @click="flipDefault = !flipDefault">
                        <div class="field-toggle-slider"></div>
                    </button>
                    <div :class="flipDefault ? 'active' : ''">gloss</div>
                </div>
            </div>
            <div class="field-compound-toggle-wrapper">
                <div class="field-toggle-title">inflections</div>
                <div class="field-toggle-wrapper">
                    <div :class="!flipDefaultInflections ? 'active' : ''">back</div>
                    <button class="field-toggle" :class="{ active: flipDefaultInflections }"
                            @click="flipDefaultInflections = !flipDefaultInflections">
                        <div class="field-toggle-slider"></div>
                    </button>
                    <div :class="flipDefaultInflections ? 'active' : ''">front</div>
                </div>
            </div>
            <div class="field-toggle-wrapper">
                <button class="field-toggle" :class="{ active: showTranslit }"
                        @click="showTranslit = !showTranslit">
                    <div class="field-toggle-slider"></div>
                </button>
                <div>Show Transcription</div>
            </div>
            <div class="field-toggle-wrapper">
                <button class="field-toggle" :class="{ active: showTerm }"
                        @click="showTerm = !showTerm">
                    <div class="field-toggle-slider"></div>
                </button>
                <div>Show Term (Back)</div>
            </div>
        </div>
        <div class="model-list index-list">
            <DeckItem :model="deck"/>
        </div>
        <WindowSection :visible="false">
            <template #title>
                <h2>term</h2>
            </template>
            <template #content>
                <div class="model-list index-list">
                    <TermItem :model="cards[currentSlideIndex]"
                              :glossId="cards[currentSlideIndex].deckPivot.gloss_id ?? null"/>
                </div>
            </template>
        </WindowSection>
    </div>

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
