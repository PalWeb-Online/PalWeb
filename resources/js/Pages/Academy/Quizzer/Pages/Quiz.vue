<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {computed, ref} from "vue";
import QuestionSelectGloss from "../UI/QuestionSelectGloss.vue";
import AppTip from "../../../../components/AppTip.vue";
import AppButton from "../../../../components/AppButton.vue";
import ToggleSingle from "../../../../components/ToggleSingle.vue";
import QuestionInputInflection from "../UI/QuestionInputInflection.vue";
import QuizzerWindow from "../UI/QuizzerWindow.vue";
import QuestionSelectTerm from "../UI/QuestionSelectTerm.vue";
import LoadingSpinner from "../../../../Shared/LoadingSpinner.vue";

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
            <p v-if="QuizzerStore.settings.quizType === 'term-gloss'">
                Select the meaning of the Arabic term in English.</p>
            <p v-else-if="QuizzerStore.settings.quizType === 'term-inflection'">
                Write the indicated inflection of the Term in Arabic.</p>
            <p v-else-if="QuizzerStore.settings.quizType === 'sentence-term'">
                Select the Term that best fits the blank in the Sentence. <b>Terms are listed in their Dictionary form,
                not necessarily as they would be expected to appear in the Sentence.</b></p>
        </AppTip>
        <div class="quiz-settings-wrapper" style="justify-content: space-around">
            <ToggleSingle v-model="showTranslit" label="Show Transcription"/>
            <ToggleSingle v-if="QuizzerStore.settings.quizType === 'term-gloss'"
                          v-model="showInflections" label="Show Inflections"/>
        </div>
    </QuizzerWindow>

    <div class="quiz-container" v-if="!QuizzerStore.data.isLoading">
        <QuestionSelectGloss v-if="QuizzerStore.settings.quizType === 'term-gloss'"
                             v-for="(question, index) in QuizzerStore.quiz"
                             :question="question"
                             :index="index"
                             :showTranslit="showTranslit"
                             :showInflections="showInflections"
        />
        <QuestionInputInflection v-if="QuizzerStore.settings.quizType === 'term-inflection'"
                                 v-for="(question, index) in QuizzerStore.quiz"
                                 :question="question"
                                 :index="index"
                                 :showTranslit="showTranslit"
        />
        <QuestionSelectTerm v-if="QuizzerStore.settings.quizType === 'sentence-term'"
                            v-for="(question, index) in QuizzerStore.quiz"
                            :question="question" :index="index"
                            :showTranslit="showTranslit"
        />
    </div>
    <LoadingSpinner v-else/>

    <AppButton :disabled="!isValidRequest" @click="QuizzerStore.submitQuiz" label="Submit"
               style="margin-block-end: 3.2rem"/>
</template>
