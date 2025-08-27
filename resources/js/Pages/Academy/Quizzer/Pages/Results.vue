<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import AnswerItem from "../UI/AnswerItem.vue";
import {computed} from "vue";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/Actions/DeckActions.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";

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

const hasNavigationGuard = computed(() => {
    return !QuizzerStore.data.isSaved;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>
<template>
    <div class="window-container">
        <div class="window-header">
            <button @click="router.get(route(`quizzer.${QuizzerStore.data.quizType}`, QuizzerStore.data.model.id))"
                    class="material-symbols-rounded">arrow_back
            </button>
            <div class="window-header-url">www.palweb.app/academy/quizzer/{{ QuizzerStore.data.quizType }}/{deck}</div>
        </div>
        <div class="window-section-head">
            <h1>Deck</h1>
            <PinButton modelType="deck" :model="QuizzerStore.data.model"/>
            <DeckActions :model="QuizzerStore.data.model"/>
        </div>
        <div class="window-content-head">
            <div class="window-content-head-title">{{ QuizzerStore.data.model?.name }}</div>
        </div>
        <div class="window-section-head">
            <h2>Results</h2>
            <PopupWindow title="Quizzer">
                <p>Because the application automatically generates decoy choices by randomly pulling from the
                    Glosses of
                    other Terms in the Deck or Dictionary, it's possible for a valid answer to the question to
                    appear as
                    a decoy (i.e. incorrect answer); this is normally rare, but is possible for exactly synonymous
                    Terms, especially if they are placed in the same Deck & decoys are pulled from the Deck. If you
                    think one of your answers should have been accepted for this or any other reason, you can
                    override the result. Remember that your score will not be saved unless you choose to do so.</p>
            </PopupWindow>
        </div>
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
        <div class="quiz-answer-array">
            <AnswerItem v-for="(question, index) in QuizzerStore.quiz" :key="index"
                        :question="question" :index="index"/>
        </div>
        <div class="window-footer">
            <button @click="QuizzerStore.saveScore" :disabled="QuizzerStore.data.isSaved">save results</button>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You haven't saved your Score yet. Are you sure you want to leave this page? Your Score for this Quiz will not be saved."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
