<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import AppButton from "../../../../components/AppButton.vue";

const QuizzerStore = useQuizzerStore();

const props = defineProps({
    question: Object,
    index: Number,
    showTranslit: Boolean,
    showInflections: Boolean,
})

const toggleSelection = (index) => {
    if (QuizzerStore.quiz.questions[props.index].selection === Number(index)) {
        QuizzerStore.quiz.questions[props.index].selection = null;
    } else {
        QuizzerStore.quiz.questions[props.index].selection = Number(index);
    }
}
</script>
<template>
    <div class="quiz-question">
        <div class="term-flashcard">
            <div class="term-flashcard-front">
                <div class="term-flashcard-term">
                    <div>{{ question.term.term }}</div>
                    <div v-show="showTranslit">{{ question.term.translit }}</div>
                </div>

                <div v-show="showInflections && question.term.inflections.length > 0"
                     class="term-flashcard-inflections">
                    <div v-for="inflection in question.term.inflections" class="term-flashcard-inflection-item">
                        <div>{{ inflection.inflection }}</div>
                        <div v-show="showTranslit">{{ inflection.translit }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="quiz-question-options">
            <AppButton v-for="(option, i) in question.options"
                    :class="{'selected': question.selection === Number(i)}"
                    @click="toggleSelection(i)" :label="option"/>
        </div>
    </div>
</template>
