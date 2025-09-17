<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";

const UserStore = useUserStore();
const QuizzerStore = useQuizzerStore();
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
            deck: QuizzerStore.data.model.id,
            term: props.question.term.id
        }));

        isPresent.value = response.data.isPresent;
        NotificationStore.addNotification(response.data.message);

    } catch (error) {
        console.error('Deck Toggle Failed', error);
    }
};

const recalculate = () => {
    QuizzerStore.score.results[props.index].correct = true;
    QuizzerStore.scoreQuiz();
    QuizzerStore.data.isSaved = false;
}
</script>
<template>
    <div class="quiz-answer-wrapper">
        <div class="quiz-answer" :class="{'incorrect': !isCorrect}">
            <div class="quiz-answer-correct">
                <div>
                    {{ question.term.term }}
                </div>
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

            <template v-if="QuizzerStore.data.model">
                <template v-if="UserStore.user.id === QuizzerStore.data.model.author.id">
                    <button @click="toggleTerm">{{ isPresent ? 'Remove from' : 'Add to' }} Deck</button>
                </template>
                <button v-if="!isCorrect" type="button" @click="recalculate">Mark as Correct</button>
            </template>
        </div>
    </div>
</template>
