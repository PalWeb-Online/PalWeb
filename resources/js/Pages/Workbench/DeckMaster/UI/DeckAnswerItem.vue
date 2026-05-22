<script setup>
import {computed} from "vue";
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
                <button v-if="!isCorrect" type="button" @click="markCorrect">Mark as Correct</button>
            </template>
        </div>
    </div>
</template>

<style scoped lang="scss">
.quiz-answer-wrapper {
    display: grid;
    gap: 0.8rem;

    .quiz-answer-options {
        display: flex;
        flex-flow: row wrap;
        gap: 0.2rem 1.6rem;
        margin-inline: 1.2rem;

        a, button {
            text-align: start;
            font-weight: 700;
            font-size: 1.4rem;
            font-family: var(--body-font);
            color: var(--color-accent-dark);

            &:hover {
                text-decoration: underline;
            }
        }

        a {
            color: var(--color-medium-primary);
        }
    }
}

.quiz-answer {
    display: grid;
    gap: 0.4rem;
    padding: 1.6rem 2.0rem;
    border-radius: 0.8rem;
    background: var(--color-pastel-light);

    &.incorrect {
        background: var(--color-accent-light);

        .quiz-answer-user {
            color: var(--color-accent-dark);
        }
    }

    .quiz-answer-correct {
        display: flex;
        flex-flow: row-reverse wrap;
        align-items: center;
        font-weight: 700;
        color: var(--color-dark-primary);

        & > *:first-child {
            flex: 1 0 auto;
            font-family: var(--mono-font);
            font-size: 2.4rem;
            direction: rtl;
        }

        & > *:last-child {
            flex: 0 1 auto;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            font-family: var(--body-font);
            font-size: 1.8rem;
        }
    }

    .quiz-answer-correct.sentence {
        & > *:first-child {
            font-size: 1.6rem;
        }

        & > *:last-child {
            font-size: 1.4rem;
            justify-self: end;
            font-family: var(--mono-font);
        }
    }

    .quiz-answer-user {
        display: grid;
        gap: 0.4rem;
        font-size: 1.4rem;
        padding-block-start: 0.8rem;
        color: var(--color-dark-primary);
        border-block-start: 0.1rem dotted currentColor;
    }
}
</style>
