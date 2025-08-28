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
    if (QuizzerStore.quiz.type === 'select') {
        return props.question.selection === props.question.answer;

    } else if (QuizzerStore.quiz.type === 'input') {
        return props.question.answer.includes(props.question.input);
    }
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
    if (QuizzerStore.quiz.type === 'select') {
        QuizzerStore.quiz.questions[props.index].selection = QuizzerStore.quiz.questions[props.index].answer;

    } else if (QuizzerStore.quiz.type === 'input') {
        QuizzerStore.quiz.questions[props.index].input = QuizzerStore.quiz.questions[props.index].answer[0];
    }

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
                <div v-if="QuizzerStore.quiz.type === 'select'">
                    {{ question.options[question.answer] }}
                </div>
                <div v-else-if="QuizzerStore.quiz.type === 'input'">
                    <span style="font-size: 1.4rem">{{ question.prompt }}.</span>
                    <span v-for="ans in question.answer">{{ ans }}</span>
                </div>
            </div>
            <div class="quiz-answer-user" v-if="!isCorrect || QuizzerStore.quiz.type === 'input'">
                <div>you said:
                    <span style="font-weight: 700" v-if="QuizzerStore.quiz.type === 'select'">
                        {{ question.options[question.selection] }}
                    </span>
                    <span style="font-weight: 700" v-else-if="QuizzerStore.quiz.type === 'input'">
                        {{ question.input }}
                    </span>
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
