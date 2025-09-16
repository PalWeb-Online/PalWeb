<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {computed} from "vue";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";
import QuizzerWindow from "../UI/QuizzerWindow.vue";
import ScoreStats from "../../../../components/ScoreStats.vue";
import ScoreDetail from "../../../../components/ScoreDetail.vue";

const QuizzerStore = useQuizzerStore();

const hasNavigationGuard = computed(() => {
    return !QuizzerStore.data.isSaved;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>
<template>
    <QuizzerWindow>
        <ScoreStats :model="QuizzerStore.data.model"/>
        <div class="window-section-head">
            <h2>Results</h2>
            <PopupWindow title="Quizzer">
                <div class="h1">Results</div>
                <p>Sometimes humans are smarter than machines. The Quizzer automatically generates Quizzes & grades them
                    through a simple matching operation, so it's possible in certain cases for an answer that should
                    have been accepted to be marked as incorrect. That's why I've provided a <b>Mark as Correct</b>
                    option, so that you can be a part of the grading process. After comparing your answer to the one
                    expected by the application, you can override the result if you think it should have been accepted.
                </p>
                <p>Remember that your Score is not saved automatically, so you can adjust the results as needed before
                    saving your Score manually. You can also exit the Results page without saving if you want to discard
                    the Score.</p>
                <div class="h2">Use Cases</div>
                <p>In a <b>Glosses Quiz</b>, it's possible for synonymous Glosses to be offered as options
                    for the same Term, making the question confusing or even impossible to answer reliably. You might
                    select a Gloss that is valid for that Term, but not be the selection expected by the application.
                    You can use the Dictionary to verify the validity of your response.</p>
                <p>In an <b>Inflections Quiz</b>, your response will be compared exactly to the expected answer, with no
                    orthographic flexibility. But Palestinian Arabic has no formal orthography, so it would be unfair to
                    mark a response as incorrect for not having the formally correct spelling of the hamze, for example.
                    Generally speaking, PalWeb follows Standard Arabic orthography, adjusted to reflect the phonology of
                    Palestinian Arabic (see the <b>Wiki</b>), so you may actually want to test yourself against this.
                    How strictly you are graded is up to you to decide on the basis of your learning goals.
                </p>
            </PopupWindow>
        </div>
        <AppTip>
            <p>If you think one of your answers should have been accepted, feel free to <b>Mark as Correct</b> (see <b>Help</b>).
            </p>
        </AppTip>

        <ScoreDetail :score="QuizzerStore.score"/>

        <div class="window-footer">
            <button @click="QuizzerStore.saveScore" :disabled="QuizzerStore.data.isSaved">save & quit</button>
        </div>
    </QuizzerWindow>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You haven't saved your Score yet. Are you sure you want to leave this page? Your Score for this Quiz will not be saved."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
