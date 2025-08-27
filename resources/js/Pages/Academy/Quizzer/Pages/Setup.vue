<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {ref} from "vue";
import DeckItem from "../../../../components/DeckItem.vue";
import ToggleDouble from "../../../../components/ToggleDouble.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";
import TermItem from "../../../../components/TermItem.vue";

const QuizzerStore = useQuizzerStore();

const quizSettings = ref({
    allGlosses: false,
    anyGloss: false
});
</script>
<template>
    <div class="window-section-head">
        <h2>Setup</h2>
        <PopupWindow title="Quizzer">
            <div>Setup</div>
            <p>Welcome to the Quizzer, where you can dynamically generate Quizzes for different models on PalWeb. When
                Quizzing a Deck, the format of the questions is multiple-choice & you may toggle between the following
                options:</p>
            <ul>
                <li><b>Decoy Source</b></li>
                <ul>
                    <li><b>Deck</b> (Default) — Incorrect answers are Glosses of other Terms in the Deck.</li>
                    <li><b>All</b> — Incorrect answers are Glosses of any Terms in the Dictionary.</li>
                </ul>
                <li><b>Any Gloss</b></li>
                <ul>
                    <li><b>No</b> (Default) — Glosses in the pool of answers & decoys are pulled specifically from among
                        those indicated in the Deck. Choose this if you're learning specific meanings of these Terms.
                    </li>
                    <li><b>Yes</b> — Glosses in the pool of answers & decoys are pulled from among any of the Glosses of
                        the Terms in the Deck. Choose this if you're learning all meanings of these Terms.
                    </li>
                </ul>
            </ul>
            <p>As a rule of thumb, try to use Decks with at
                least 10 Terms to ensure the Quiz is sufficiently challenging & to avoid unintended results.</p>
            <p>Because these quizzes are generated automatically by the application, certain unexpected results are
                possible in fringe cases, especially with small Decks:</p>
            <ul>
                <li>If <b>Decoy Source</b> is <b>Deck</b> & the Deck has two or fewer Terms, then it's possible that
                    there won't be enough Glosses to pull as decoys; as a result, there may be fewer than three options
                    per question.
                </li>
                <li>If <b>Decoy Source</b> is <b>Deck</b> & the Deck contains synonyms — especially perfect synonyms —
                    then the fewer Terms there are in the Deck the likelier it is that synonymous Glosses will be listed
                    as options for the same Term, making the question confusing or even impossible to answer reliably.
                    While this is technically always possible when <b>Decoy Source</b> is <b>All</b>, the chance of this
                    happening is infinitesimal.
                </li>
            </ul>
        </PopupWindow>
    </div>
    <AppTip>
        <p>
            The Quiz will have {{ QuizzerStore.data.model?.terms_count }} questions. Adjust how you'd like the Quiz to
            be generated.
        </p>
        <p v-if="QuizzerStore.data.model?.terms_count <= 5"><b>This Deck has 5 or fewer Terms.</b> You should select
            "All" as the decoy source to avoid unintended results (see <b>Help</b>).</p>
    </AppTip>
    <div class="quiz-container">
        <div class="score-highlight-wrapper">
            <div class="score-highlight-container">
                <div class="score-highlight">
                    <div>Latest Score</div>
                    <div class="featured-title">90%</div>
                </div>
                <div class="score-highlight">
                    <div>Highest Score</div>
                    <div class="featured-title">90%</div>
                </div>
                <div class="score-highlight">
                    <div>Average Score</div>
                    <div class="featured-title">90%</div>
                </div>
            </div>
            <a>See Score History</a>
        </div>

        <DeckItem v-if="QuizzerStore.data.model" :model="QuizzerStore.data.model"/>

        <div class="model-list">
            <TermItem v-for="term in QuizzerStore.data.model?.terms" :model="term" :glossId="term.deckPivot.gloss_id"/>
        </div>

        <div style="display: flex; justify-content: center; gap: 1.6rem;">
            <ToggleDouble v-model="quizSettings.allGlosses" label="decoy source" option-a="deck" option-b="all"/>
            <ToggleDouble v-model="quizSettings.anyGloss" label="any gloss" option-a="no" option-b="yes"/>
        </div>
    </div>
    <div class="window-footer">
        <button @click="QuizzerStore.startQuiz(quizSettings)">Start Quiz</button>
    </div>
</template>
