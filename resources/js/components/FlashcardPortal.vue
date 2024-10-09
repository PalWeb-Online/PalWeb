<script setup>
import TermFlashcard from "./TermFlashcard.vue";
import {onMounted, onUnmounted, ref} from "vue";
import 'vue3-carousel/dist/carousel.css';
import {Carousel, Pagination, Slide} from 'vue3-carousel';

const props = defineProps({
    deckId: String,
});

const deck = ref({});
const terms = ref([]);
const imageURL = ref([]);
const isUser = ref([]);
const isAdmin = ref([]);
const carouselRef = ref(null);
const currentSlideIndex = ref(0);
const isSliding = ref(false);
const flipDefault = ref(false);
const showTerm = ref(true);
const showTranslit = ref(false);
const flipDefaultInflections = ref(false);
const defaultOrder = ref([]);

onMounted(() => {
    getDeck(props.deckId);
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

const getDeck = async (deckId) => {
    try {
        const response = await axios.get("/dashboard/flashcards/" + deckId + "/get");
        deck.value = response.data.deck;
        terms.value = response.data.terms;
        defaultOrder.value = [...response.data.terms];
        imageURL.value = response.data.imageURL;
        isUser.value = response.data.isUser;
        isAdmin.value = response.data.isAdmin;

    } catch (error) {
        console.error('Loading Failed', error);
    }
};

const handleKeydown = (event) => {
    if (!carouselRef.value || isSliding.value) return;

    switch (event.key) {
        case ' ':
            event.preventDefault();
            flipDefault.value = !flipDefault.value;
            break;
        case 'ArrowRight':
        case 'd':
            carouselRef.value.next();
            break;
        case 'ArrowLeft':
        case 'a':
            carouselRef.value.prev();
            break;
    }
};

const handleSlideStart = () => {
    isSliding.value = true;
};

const handleSlideEnd = ({currentSlideIndex: newIndex}) => {
    currentSlideIndex.value = newIndex;
    isSliding.value = false;
};

const reset = () => {
    terms.value = [...defaultOrder.value];

    const flashcards = document.querySelectorAll('.term-flashcard');
    flashcards.forEach(card => {
        if (flipDefault.value) {
            card.classList.add('flipped');
        } else {
            card.classList.remove('flipped');
        }
    });

    carouselRef.value?.slideTo(0);
};

const shuffle = () => {
    for (let i = terms.value.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [terms.value[i], terms.value[j]] = [terms.value[j], terms.value[i]];
    }

    carouselRef.value?.slideTo(0);
};

</script>

<template>
    <div class="flashcard-portal-box">
        <div>Options</div>
        <div>
            <button @click="reset">Reset Cards</button>
            <button @click="shuffle">Shuffle Terms</button>
            <button @click="flipDefault = !flipDefault">Default Side: {{ flipDefault ? 'Gloss' : 'Term' }}</button>
            <button @click="flipDefaultInflections = !flipDefaultInflections">Show Inflections: {{ flipDefaultInflections ? 'Front' : 'Back' }}</button>
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

    <Carousel
        :items-to-show="1"
        :wrap-around="true"
        ref="carouselRef"
        @slide-start="handleSlideStart"
        @slide-end="handleSlideEnd"
    >
        <template #slides>
            <Slide v-for="(term, index) in terms" :key="term.id">
                <TermFlashcard
                    :term="term"
                    :imageURL="imageURL"
                    :isUser="isUser"
                    :isAdmin="isAdmin"
                    :isActive="index === currentSlideIndex && !isSliding"
                    :flipDefault="flipDefault"
                    :showTerm="showTerm"
                    :showTranslit="showTranslit"
                    :flipDefaultInflections="flipDefaultInflections"
                />
            </Slide>
        </template>
        <template #addons>
            <pagination/>
            <div class="flashcard-index">{{ currentSlideIndex + 1 }} out of {{ terms.length }}</div>
        </template>
    </Carousel>

    <div class="flashcard-portal-box">
        <div>Controls</div>
        <div>
            <div><b>Left</b> / <b>A</b> Previous Card</div>
            <div><b>Right</b> / <b>D</b> Next Card</div>
            <div><b>Down</b> / <b>S</b> Flip Card</div>
            <div><b>Up</b> / <b>W</b> Play Audio</div>
            <div><b>Space</b> Toggle Default Side</div>
        </div>
    </div>
</template>
