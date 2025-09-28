<script setup>
import {useDeckStudyStore} from "../Stores/DeckStudyStore.js";
import {computed, ref} from "vue";
import QuestionSelectGloss from "../UI/QuestionSelectGloss.vue";
import AppTip from "../../../../components/AppTip.vue";
import AppButton from "../../../../components/AppButton.vue";
import ToggleSingle from "../../../../components/ToggleSingle.vue";
import QuestionInputInflection from "../UI/QuestionInputInflection.vue";
import QuizzerWindow from "../UI/QuizzerWindow.vue";
import QuestionSelectTerm from "../UI/QuestionSelectTerm.vue";
import LoadingSpinner from "../../../../Shared/LoadingSpinner.vue";

const DeckStudyStore = useDeckStudyStore();

const isValidRequest = computed(() => {
    return !DeckStudyStore.quiz.some(question => !question.response)
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
            <p v-if="DeckStudyStore.settings.quizType === 'glosses'">
                Select the meaning of the Arabic term in English.</p>
            <p v-else-if="DeckStudyStore.settings.quizType === 'inflections'">
                Write the indicated inflection of the Term in Arabic.</p>
            <p v-else-if="DeckStudyStore.settings.quizType === 'sentences'">
                Select the Term that best fits the blank in the Sentence. <b>Terms are listed in their Dictionary form,
                not necessarily as they would be expected to appear in the Sentence.</b></p>
        </AppTip>
        <div class="quiz-settings-wrapper" style="justify-content: space-around">
            <ToggleSingle v-model="showTranslit" label="Show Transcription"/>
            <ToggleSingle v-if="DeckStudyStore.settings.quizType === 'glosses'"
                          v-model="showInflections" label="Show Inflections"/>
        </div>
    </QuizzerWindow>

    <div class="quiz-container" v-if="!DeckStudyStore.data.isLoading">
        <QuestionSelectGloss v-if="DeckStudyStore.settings.quizType === 'glosses'"
                             v-for="(question, index) in DeckStudyStore.quiz"
                             :question="question"
                             :index="index"
                             :showTranslit="showTranslit"
                             :showInflections="showInflections"
        />
        <QuestionInputInflection v-if="DeckStudyStore.settings.quizType === 'inflections'"
                                 v-for="(question, index) in DeckStudyStore.quiz"
                                 :question="question"
                                 :index="index"
                                 :showTranslit="showTranslit"
        />
        <QuestionSelectTerm v-if="DeckStudyStore.settings.quizType === 'sentences'"
                            v-for="(question, index) in DeckStudyStore.quiz"
                            :question="question" :index="index"
                            :showTranslit="showTranslit"
        />
    </div>
    <LoadingSpinner v-else/>

    <button class="material-symbols-rounded" :disabled="!isValidRequest" @click="DeckStudyStore.submitQuiz">check</button>
</template>
