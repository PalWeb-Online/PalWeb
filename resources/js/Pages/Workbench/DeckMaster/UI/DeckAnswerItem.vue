<script setup>
import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {useDeck} from "../../../../composables/Deck.js";

const UserStore = useUserStore();
const {toggleTerm} = useDeck();

const props = defineProps({
    exercise: Object,
    deck: {type: Object, default: null},
    markCorrect: {type: Function, default: null}
})

const isCorrect = computed(() => {
    return props.exercise.correct;
});
</script>
<template>
    <div class="quiz-answer-wrapper">
        <div class="quiz-answer" :class="{'incorrect': !isCorrect}">
            <div v-if="exercise.sentence" class="quiz-answer-correct sentence" style="display: grid">
                <div>{{ exercise.prompt }}</div>
                <div>
                    <span style="color: var(--color-medium-primary)">{{ exercise.sentence.sentence }}</span>
                    <--
                    <span v-for="ans in exercise.answer" style="font-size: 2.0rem; color: var(--color-medium-primary)">
                        {{ ans }}
                    </span>
                </div>
            </div>
            <div v-else class="quiz-answer-correct term">
                <div>{{ exercise.term.term }}</div>
                <div>
                    <span v-if="exercise.prompt" style="font-size: 1.4rem">{{ exercise.prompt }}.</span>
                    <span v-for="ans in exercise.answer">{{ ans }}</span>
                </div>
            </div>

            <div class="quiz-answer-user">
                <div>you said:
                    <span style="font-weight: 700">{{ exercise.response }}</span>
                </div>
            </div>
        </div>
        <div class="quiz-answer-options">
            <a :href="route('terms.show', exercise.term.slug)" target="_blank">See in Dictionary</a>
            <template v-if="deck">
                <template v-if="UserStore.user.id === deck.author.id">
                    <button @click="toggleTerm(deck, exercise.term)">
                        {{ deck.terms.some(term => term.id === exercise.term.id) ? 'Remove from' : 'Add to' }} Deck
                    </button>
                </template>
            </template>
            <button v-if="!isCorrect" type="button" @click="markCorrect">Mark as Correct</button>
        </div>
    </div>
</template>
