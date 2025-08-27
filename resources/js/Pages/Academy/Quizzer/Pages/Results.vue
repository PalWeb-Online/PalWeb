<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import AnswerItem from "../UI/AnswerItem.vue";
import {computed, ref} from "vue";
import DeckItem from "../../../../components/DeckItem.vue";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";
import {useNavGuard} from "../../../../composables/NavGuard.js";

const QuizzerStore = useQuizzerStore();

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const rawScore = computed(() => {
    return QuizzerStore.score / QuizzerStore.quiz.length;
});

const formattedScore = computed(() => {
    return formatter.format(rawScore.value);
});

const scoreMessage = computed(() => {
    if (rawScore.value === 1) {
        return "Flawless!";
    }
    if (rawScore.value >= 0.85) {
        return "Impressive!";
    }
    if (rawScore.value >= 0.7) {
        return "You’re getting there!";
    }
    if (rawScore.value >= 0.5) {
        return "Off to a great start!";
    }
    return "Better keep practicing!";
});

const showOverridePrompt = ref(false);

const hasNavigationGuard = computed(() => {
    return !QuizzerStore.data.isSaved;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>
<template>
    <div class="window-section-head">
        <h2>Results</h2>
    </div>
    <div class="quiz-container">
        <DeckItem v-if="QuizzerStore.data.model" :model="QuizzerStore.data.model"/>

        <div class="quiz-results">
            <div class="quiz-results-callout featured-title">new record!</div>
            <div class="score-figure featured-title">{{ formattedScore }}</div>
            <div class="score-feedback">
                <div>{{ scoreMessage }}</div>
                <div style="font-weight: 700">You answered {{ QuizzerStore.score }} out of {{
                        QuizzerStore.quiz.length
                    }}
                    questions correctly.
                </div>
                <div>Review your answers below.</div>
            </div>
        </div>
        <div class="quiz-override-wrapper">
            <button @click="showOverridePrompt = !showOverridePrompt">See something wrong?</button>
            <div v-if="showOverridePrompt" class="quiz-override">
                <p>Because the application automatically generates decoy choices by randomly pulling from the Glosses of
                    other Terms in the Deck or Dictionary, it's possible for a valid answer to the question to appear as
                    a decoy (i.e. incorrect answer); this is normally rare, but is possible for exactly synonymous
                    Terms, especially if they are placed in the same Deck & decoys are pulled from the Deck. If you
                    think one of your answers should have been accepted for this or any other reason, you can
                    override the result. Remember that your score will not be saved unless you choose to do so.</p>
            </div>
        </div>
        <div class="quiz-answer-array">
            <AnswerItem v-for="(question, index) in QuizzerStore.quiz" :key="index"
                        :question="question" :index="index"/>
        </div>
    </div>
    <div class="window-footer">
        <button @click="QuizzerStore.startOver">new quiz</button>
        <button @click="QuizzerStore.saveScore" :disabled="QuizzerStore.data.isSaved">save results</button>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You haven't saved your Score yet. Are you sure you want to leave this page? Your Score for this Quiz will not be saved."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
