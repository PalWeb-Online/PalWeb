<script setup>
import {useDeckStudyStore} from "../Stores/DeckStudyStore.js";
import AppButton from "../../../../components/AppButton.vue";
import {ref} from "vue";

const DeckStudyStore = useDeckStudyStore();

const props = defineProps({
    question: Object,
    index: Number,
    showTranslit: Boolean,
    showInflections: Boolean,
})

const toggleSelection = (index) => {
    if (DeckStudyStore.quiz[props.index].response === Number(index)) {
        DeckStudyStore.quiz[props.index].response = null;
    } else {
        DeckStudyStore.quiz[props.index].response = Number(index);
        startTypingEffect(props.question.options[index]);
    }
}

const displayedText = ref("");
let typingInterval = null;

const startTypingEffect = (text) => {
    if (typingInterval) {
        clearInterval(typingInterval);
        typingInterval = null;
    }

    let currentIndex = 0;
    displayedText.value = "";

    typingInterval = setInterval(() => {
        if (currentIndex < text.length) {
            displayedText.value += text[currentIndex];
            currentIndex++;
        } else {
            clearInterval(typingInterval);
            typingInterval = null;
        }
    }, 25);
};
</script>
<template>
    <div class="quiz-question">
        <div class="term-flashcard quiz-glosses flipped" style="cursor: default">
            <div class="term-flashcard-back">
                <div class="term-flashcard-head">
                    <div class="term-flashcard-headword">
                        <div>{{ question.term.term }}</div>
                        <div v-show="showTranslit">({{ question.term.translit }})</div>
                    </div>
                    <div v-show="showInflections && question.term.inflections.length > 0"
                         class="term-flashcard-inflections">
                        <div v-for="inflection in question.term.inflections" class="term-flashcard-inflection-item">
                            <div>{{ inflection.inflection }}</div>
                            <div v-show="showTranslit">({{ inflection.translit }})</div>
                        </div>
                    </div>
                </div>
                <div class="term-flashcard-glosses">
                    <div v-show="DeckStudyStore.quiz[props.index].response">
                        {{ displayedText }}
                    </div>
                    <div></div>
                </div>
            </div>
        </div>

        <div class="exercise--select-choices">
            <button v-for="(option, i) in question.options"
                       :class="{'selected': question.response === Number(i)}"
                       @click="toggleSelection(i)">
                {{ option }}
            </button>
        </div>
    </div>
</template>
