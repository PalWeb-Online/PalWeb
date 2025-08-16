<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";

const QuizzerStore = useQuizzerStore();

const props = defineProps({
    question: Object,
    index: Number,
    showResults: {type: Boolean, default: false},
})
</script>
<template>
    <div class="quiz-question" :class="{ 'show-results': showResults }">
        <div class="quiz-question-prompt">{{ question.prompt }}</div>
        <div class="quiz-question-options">
            <button v-if="!showResults" v-for="(option, i) in question.options"
                    :class="{'selected': QuizzerStore.quiz[index].selection === Number(i)}"
                @click="QuizzerStore.quiz[index].selection = Number(i)">{{ option }}</button>

            <div v-else v-for="(option, i) in question.options"
                    :class="{
                        'correct': QuizzerStore.quiz[index].answer === Number(i),
                        'incorrect': QuizzerStore.quiz[index].selection === Number(i) && QuizzerStore.quiz[index].answer !== Number(i)
            }"
             >{{ option }}</div>
        </div>
    </div>
</template>
