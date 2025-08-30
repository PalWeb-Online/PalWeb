<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {computed, ref} from "vue";
import QuestionSelect from "../UI/QuestionSelect.vue";
import AppTip from "../../../../components/AppTip.vue";
import AppButton from "../../../../components/AppButton.vue";
import ToggleSingle from "../../../../components/ToggleSingle.vue";
import QuestionInput from "../UI/QuestionInput.vue";
import QuizzerWindow from "../UI/QuizzerWindow.vue";

const QuizzerStore = useQuizzerStore();

const isValidRequest = computed(() => {
    return !QuizzerStore.quiz.some(question => !question.response)
});

const showInflections = ref(false);
const showTranslit = ref(false);
</script>
<template>
    <QuizzerWindow>
        <div class="window-section-head">
            <h2>Quiz</h2>
        </div>
        <AppTip>
            <p v-if="!QuizzerStore.settings.typeInput">Select the meaning of the Arabic term in English.</p>
            <p v-else>Write the indicated inflection of the Term in Arabic.</p>
        </AppTip>
        <div class="quiz-settings-wrapper" style="justify-content: space-around">
            <ToggleSingle v-model="showTranslit" label="Show Transcription"/>
            <ToggleSingle v-if="!QuizzerStore.settings.typeInput" v-model="showInflections" label="Show Inflections"/>
        </div>
    </QuizzerWindow>

    <div class="quiz-container">
        <QuestionSelect v-if="!QuizzerStore.settings.typeInput"
                        v-for="(question, index) in QuizzerStore.quiz"
                        :question="question"
                        :index="index"
                        :showTranslit="showTranslit"
                        :showInflections="showInflections"
        />
        <QuestionInput v-else
                       v-for="(question, index) in QuizzerStore.quiz"
                       :question="question"
                       :index="index"
                       :showTranslit="showTranslit"
        />
    </div>
    <AppButton :disabled="!isValidRequest" @click="QuizzerStore.submitQuiz" label="Submit" style="margin-block-end: 3.2rem"/>
</template>
