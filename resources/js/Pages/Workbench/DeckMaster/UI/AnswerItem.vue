<script setup>
import {useDeckStudyStore} from "../Stores/DeckStudyStore.js";
import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";

const UserStore = useUserStore();
const DeckStudyStore = useDeckStudyStore();
const NotificationStore = useNotificationStore();

const props = defineProps({
    question: Object,
    index: Number,
})

const isCorrect = computed(() => {
    return props.question.correct;
})

const isPresent = ref(true);

const toggleTerm = async () => {
    try {
        const response = await axios.post(route('decks.term.toggle', {
            deck: DeckStudyStore.data.deck.id,
            term: props.question.term.id
        }));

        isPresent.value = response.data.isPresent;
        NotificationStore.addNotification(response.data.message);

    } catch (error) {
        console.error('Deck Toggle Failed', error);
    }
};

const recalculate = () => {
    DeckStudyStore.score.results[props.index].correct = true;
    DeckStudyStore.scoreQuiz();
    DeckStudyStore.data.isSaved = false;
}
</script>
<template>
    <div class="quiz-answer-wrapper">
        <div class="quiz-answer" :class="{'incorrect': !isCorrect}">
            <div v-if="question.sentence" class="quiz-answer-correct sentence" style="display: grid">
                <div>{{ question.prompt }}</div>
                <div>
                    <span style="color: var(--color-medium-primary)">{{ question.sentence.sentence }}</span>
                    <--
                    <span v-for="ans in question.answer" style="font-size: 2.0rem; color: var(--color-medium-primary)">{{ ans }}</span>
                </div>
            </div>
            <div v-else class="quiz-answer-correct term">
                <div>{{ question.term.term }}</div>
                <div>
                    <span v-if="question.prompt" style="font-size: 1.4rem">{{ question.prompt }}.</span>
                    <span v-for="ans in question.answer">{{ ans }}</span>
                </div>
            </div>

            <div class="quiz-answer-user">
                <div>you said:
                    <span style="font-weight: 700">{{ question.response }}</span>
                </div>
            </div>
        </div>
        <div class="quiz-answer-options">
            <a :href="route('terms.show', question.term.slug)" target="_blank">See in Dictionary</a>
            <template v-if="DeckStudyStore.data.deck">
                <template v-if="UserStore.user.id === DeckStudyStore.data.deck.author.id">
                    <button @click="toggleTerm">{{ isPresent ? 'Remove from' : 'Add to' }} Deck</button>
                </template>
                <button v-if="!isCorrect" type="button" @click="recalculate">Mark as Correct</button>
            </template>
        </div>
    </div>
</template>
