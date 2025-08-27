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
    return QuizzerStore.quiz[props.index].selection === QuizzerStore.quiz[props.index].answer;
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
    QuizzerStore.quiz[props.index].selection = QuizzerStore.quiz[props.index].answer;
    QuizzerStore.scoreQuiz();
    QuizzerStore.data.isSaved = false;
}
</script>
<template>
    <div class="quiz-answer-wrapper">
        <div class="quiz-answer" :class="{'incorrect': !isCorrect}">
            <div class="quiz-answer-correct">
                <div>{{ question.term.term }}</div>
                <div>{{ question.options[QuizzerStore.quiz[index].answer] }}</div>
            </div>
            <div class="quiz-answer-incorrect" v-if="!isCorrect">
                <div>you said: <span
                    style="font-weight: 700">{{ question.options[QuizzerStore.quiz[index].selection] }}</span>
                </div>
            </div>
        </div>
        <div class="quiz-answer-options">
            <a :href="route('terms.show', question.term.slug)" target="_blank">See in Dictionary</a>
            <template v-if="UserStore.user.id === QuizzerStore.data.model.author.id">
                <button @click="toggleTerm">{{ isPresent ? 'Remove from' : 'Add to' }} Deck</button>
            </template>
            <button v-if="!isCorrect" type="button" @click="recalculate">Mark as Correct</button>
        </div>
    </div>
</template>
