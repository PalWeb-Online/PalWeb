<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {ref} from "vue";
import DeckFlashcard from "../../../../components/DeckFlashcard.vue";
import ToggleDouble from "../../../../components/ToggleDouble.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";

const QuizzerStore = useQuizzerStore();

const quizSettings = ref({
    allGlosses: true
});
</script>
<template>
    <div class="window-section-head">
        <h2>Setup</h2>

        <!--        <PopupWindow title="(Quizzer) Setup">-->
        <!--            <div>What is my Speaker profile?</div>-->
        <!--            <p>Your Speaker profile contains linguistic data about you that will be connected to every Recording you-->
        <!--                create, so that others can know the dialect & other sociolinguistic information behind what they're-->
        <!--                hearing. Your Speaker profile is distinct from your User profile; it does not include your name,-->
        <!--                etc. By default, however, your Recordings will link to your User profile. If you would like for your-->
        <!--                Speaker profile to remain anonymous, simply return to the Dashboard & set your User profile to-->
        <!--                Private. You can change this at any time.</p>-->
        <!--        </PopupWindow>-->
    </div>
    <div style="background: white">
        <AppTip>
            <p>You've chosen to Quiz a {{ QuizzerStore.data.quizType }}. Adjust how you'd like the Quiz to be generated.
            </p>
        </AppTip>

        <DeckFlashcard v-if="QuizzerStore.data.model" :model="QuizzerStore.data.model"/>
        <ToggleDouble v-model="quizSettings.allGlosses" label="decoy source" option-a="all" option-b="deck"/>
    </div>
    <div class="window-footer">
        <button @click="QuizzerStore.startQuiz(quizSettings)">Start Quiz</button>
    </div>
</template>
