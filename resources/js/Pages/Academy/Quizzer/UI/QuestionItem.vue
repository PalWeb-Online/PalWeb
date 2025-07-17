<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";

const QuizzerStore = useQuizzerStore();

const props = defineProps({
    question: Object,
    index: Number,
})
</script>
<template>
    <div class="quiz-question">
        <div class="quiz-question-prompt">{{ question.prompt }}</div>
        <div class="quiz-question-options">
            <button v-for="(option, i) in question.options"
                    :class="{'selected': QuizzerStore.quiz[index].selection === Number(i)}"
                @click="QuizzerStore.quiz[index].selection = Number(i)">{{ option }}</button>
        </div>
    </div>
</template>
