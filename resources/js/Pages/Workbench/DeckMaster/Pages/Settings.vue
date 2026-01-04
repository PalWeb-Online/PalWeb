<script setup>
import {useDeckStudyStore} from "../Stores/DeckStudyStore.js";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";
import ScoreStats from "../../../../components/ScoreStats.vue";
import QuizzerWindow from "../UI/QuizzerWindow.vue";
import WindowSection from "../../../../components/WindowSection.vue";
import {useUserStore} from "../../../../stores/UserStore.js";
import {watch} from "vue";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import TermItem from "../../../../components/TermItem.vue";
import ToggleSingle from "../../../../components/ToggleSingle.vue";

const UserStore = useUserStore();
const DeckStudyStore = useDeckStudyStore();
const NotificationStore = useNotificationStore();

watch(() => DeckStudyStore.settings.quizType, (newVal) => {
    if (newVal !== 'practice' && !UserStore.isStudent) {
        DeckStudyStore.settings.quizType = 'practice';
        NotificationStore.addNotification('You must be a Student to Quiz a Deck.');
    }
})
</script>
<template>
    <QuizzerWindow>
        <WindowSection :visible="false">
            <template #title>
                <h2>stats</h2>
            </template>
            <template #content>
                <ScoreStats :model="DeckStudyStore.data.deck"/>
            </template>
        </WindowSection>
        <WindowSection :visible="false">
            <template #title>
                <h2>terms</h2>
            </template>
            <template #content>
                <div class="model-list index-list">
                    <TermItem v-for="term in DeckStudyStore.data.deck?.terms" :model="term"
                              :glossId="term.deckPivot.gloss_id"/>
                </div>
                <div class="terms-count">{{ DeckStudyStore.data.deck?.terms.length }} Terms</div>
            </template>
        </WindowSection>

        <div class="window-section-head">
            <h2>Settings</h2>
            <PopupWindow title="Deck Master (Study)">
                <div class="h1">Study</div>
                <p>Welcome to the Deck Master <b>Study</b> page, where you can study Decks in a variety of modes. Use
                    <b>Practice</b> mode to view your Decks as flashcards, or dynamically generate three different types
                    of scored Quizzes: <b>Glosses</b>, <b>Inflections</b> & <b>Sentences</b>.
                </p>
                <p>Quizzes may have up to 50 (<b>Glosses</b> & <b>Inflections</b>) or 25 (<b>Sentences</b>) items. If
                    the Deck has more than that many valid Terms, a random selection of that many valid Terms will be
                    pulled from the Deck to generate the Quiz; e.g. if you have a Deck of 100 Terms, 50 valid Terms will
                    be randomly pulled from the Deck to generate the Quiz.
                </p>
                <div class="h2">Glosses Quiz</div>
                <p>In this type of Quiz, the format of the questions is multiple-choice. You will be shown a Term &
                    must select the Gloss that most closely matches it. You may toggle between the following
                    options:</p>
                <ul>
                    <li><b>Decoy Source</b></li>
                    <ul>
                        <li><b>Deck</b> (Default) — Incorrect answers are Glosses of other Terms in the Deck.</li>
                        <li><b>All</b> — Incorrect answers are Glosses of any Terms in the Dictionary.</li>
                    </ul>
                    <li><b>Any Gloss</b></li>
                    <ul>
                        <li><b>No</b> (Default) — Glosses in the pool of answers & decoys are pulled specifically from
                            among
                            those indicated in the Deck. Choose this if you're learning specific meanings of these
                            Terms.
                        </li>
                        <li><b>Yes</b> — Glosses in the pool of answers & decoys are pulled from among any of the
                            Glosses of
                            the Terms in the Deck. Choose this if you're learning all meanings of these Terms.
                        </li>
                    </ul>
                </ul>
                <p>As a rule of thumb, try to use Decks with at least 10 Terms to ensure the Quiz is sufficiently
                    challenging & to avoid unintended results.</p>
                <p>Because these quizzes are generated automatically by the application, certain unexpected results are
                    possible in fringe cases, especially with small Decks:</p>
                <ul>
                    <li>If <b>Decoy Source</b> is <b>Deck</b> & the Deck has two or fewer Terms, then it's possible that
                        there won't be enough Glosses to pull as decoys; as a result, there may be fewer than three
                        options
                        per question.
                    </li>
                    <li>If <b>Decoy Source</b> is <b>Deck</b> & the Deck contains synonyms — especially perfect synonyms
                        —
                        then the fewer Terms there are in the Deck the likelier it is that synonymous Glosses will be
                        listed
                        as options for the same Term, making the question confusing or even impossible to answer
                        reliably.
                        While this is technically always possible when <b>Decoy Source</b> is <b>All</b>, the chance of
                        this
                        happening is infinitesimal.
                    </li>
                </ul>

                <div class="h2">Inflections Quiz</div>
                <p>In this type of Quiz, the format of the questions is fill-in-the-blank. You will be shown a Term &
                    prompted with an inflectional form, which you must type in Arabic.</p>

                <div class="h2">Sentences Quiz</div>
                <p>In this type of Quiz, the format of the questions is multiple-choice. You will be shown a Sentence &
                    must select the Term that best fits the blank. Since the Sentences are selected randomly for the
                    Corpus irrespective of their complexity, the need to parse through Sentences could make it difficult
                    for beginners. (Note that the Corpus is a work in progress. Not all Glosses have Sentences, so it's
                    likely that not all Terms in the Deck will be present in the Quiz at this stage.) You may toggle
                    between the following options:</p>
                <ul>
                    <li><b>Translation</b></li>
                    <ul>
                        <li><b>On</b> (Default) — You will see the English translation of the Sentence. Decoys are other
                            Terms in the Deck. (Because the Term's Gloss will be present in the translation, the nature
                            of this Quiz is similar to that of the <b>Glosses Quiz</b>.)
                        </li>
                        <li><b>Off</b> — You will not see the English translation of the Sentence. Decoys are Terms in
                            the Dictionary belonging to different parts of speech. (If you know the contents of the Deck
                            well, the appearance of random Terms may give away the answer.)
                        </li>
                    </ul>
                    <li><b>Any Gloss</b></li>
                    <ul>
                        <li><b>No</b> (Default) — Sentences are pulled specifically from among those where the Term is
                            used in the sense indicated in the Deck. Choose this if you're learning specific meanings of
                            these Terms.
                        </li>
                        <li><b>Yes</b> — Sentences are pulled from among any of the Sentences of the Terms in the Deck.
                            Choose this if you're learning all meanings of these Terms.
                        </li>
                    </ul>
                </ul>
            </PopupWindow>
        </div>
        <AppTip>
            <p v-if="UserStore.isStudent">Select the type of Quiz & adjust how you'd like for it to be generated.</p>
            <p v-else><b>You must be a Student to enable Quizzes.</b></p>
        </AppTip>
        <div class="quiz-settings-type" :class="{
                'selected': DeckStudyStore.settings.quizType === 'practice'
            }"
             @click="DeckStudyStore.settings.quizType = 'practice'">
            <div>Practice</div>
            <p>View your Decks as flashcards, without receiving a Score.</p>
        </div>

        <div class="quiz-settings-type" :class="{
                'selected': DeckStudyStore.settings.quizType === 'glosses',
                'disabled': !UserStore.isStudent
            }"
             @click="DeckStudyStore.settings.quizType = 'glosses'">
            <div>Glosses</div>
            <p><b>(Easy)</b> Test your knowledge of Arabic vocabulary with a multiple-choice Quiz, where you must select
                the correct English meaning (Gloss) of the Arabic Term; the correct answer is
                <b>{{
                        DeckStudyStore.settings.options.strictGloss
                            ? 'the Gloss indicated in the Deck'
                            : 'any of the Term\'s Glosses'
                    }}</b>, which is shuffled with the Glosses of other Terms in the
                <b>{{ DeckStudyStore.settings.options.strictTerms ? 'Deck' : 'Dictionary' }}</b>.
            </p>
            <AppTip v-if="DeckStudyStore.data.deck?.terms_count <= 5">
                <p><b>This Deck has 5 or fewer Terms.</b> You should select
                    "All" as the decoy source to avoid unintended results (see <b>Help</b>).</p>
            </AppTip>
            <div class="settings-wrapper">
                <ToggleSingle v-model="DeckStudyStore.settings.options.strictGloss" label="strict gloss"/>
                <ToggleSingle v-model="DeckStudyStore.settings.options.strictTerms" label="strict terms"/>
            </div>
        </div>
        <div class="quiz-settings-type" :class="{
                'selected': DeckStudyStore.settings.quizType === 'inflections',
                'disabled': !UserStore.isStudent
            }"
             @click="DeckStudyStore.settings.quizType = 'inflections'">
            <div>Inflections</div>
            <p><b>(Medium)</b> Do you know your broken plurals & other forms of the Terms you've learned? Test
                yourself with a fill-in Quiz, where you must give the correct form of the indicated Term.</p>
            <p>(You will only be quizzed on Terms with Inflections, so the Quiz may have fewer questions than
                there are Terms.)</p>
        </div>
        <div class="quiz-settings-type" :class="{
                'selected': DeckStudyStore.settings.quizType === 'sentences',
                'disabled': !UserStore.isStudent
            }"
             @click="DeckStudyStore.settings.quizType = 'sentences'">
            <div>Sentences</div>
            <p><b>(Medium/Hard)</b> Can you find the right word to express yourself? Test yourself with a
                multiple-choice Quiz, where you must select the right Term to complete the Sentence,
                <b>{{
                        DeckStudyStore.settings.options.strictGloss
                            ? 'where the Term appears with the Gloss indicated in the Deck'
                            : 'which is any of the Sentences the Term appears in'
                    }}</b>; the English translation of the Sentence
                <b>{{
                        DeckStudyStore.settings.options.withTranslation
                            ? 'is provided'
                            : 'is not provided'
                    }}</b>.
            </p>
            <div class="settings-wrapper">
                <ToggleSingle v-model="DeckStudyStore.settings.options.strictGloss" label="strict gloss"/>
                <ToggleSingle v-model="DeckStudyStore.settings.options.withTranslation" label="translation"/>
            </div>
        </div>
        <div class="window-footer">
            <button v-if="DeckStudyStore.settings.quizType === 'practice'" @click="DeckStudyStore.startPractice">
                Start Practice
            </button>
            <button v-else @click="DeckStudyStore.startQuiz" :disabled="!DeckStudyStore.settings.quizType">
                Start Quiz
            </button>
        </div>
    </QuizzerWindow>
</template>
