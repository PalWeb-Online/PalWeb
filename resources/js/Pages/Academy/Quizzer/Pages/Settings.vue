<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import ToggleDouble from "../../../../components/ToggleDouble.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";
import ScoreStats from "../../../../components/ScoreStats.vue";
import QuizzerWindow from "../UI/QuizzerWindow.vue";
import WindowSection from "../../../../components/WindowSection.vue";

const QuizzerStore = useQuizzerStore();
</script>
<template>
    <QuizzerWindow>
        <WindowSection>
            <template #title>
                <h2>stats</h2>
            </template>
            <template #content>
                <ScoreStats :model="QuizzerStore.data.model"/>
            </template>
        </WindowSection>
        <!--        <p>Here are the Terms you will be quizzed on.</p>-->
        <!--        <div class="model-list index-list" style="padding-block-start: 0.8rem">-->
        <!--            <TermItem v-for="term in QuizzerStore.data.model?.terms" :model="term"-->
        <!--                      :glossId="term.deckPivot.gloss_id"/>-->
        <!--        </div>-->

        <div class="window-section-head">
            <h2>Settings</h2>
            <PopupWindow title="Quizzer">
                <div class="h1">Setup</div>
                <p>Welcome to the Quizzer, where you can dynamically generate Quizzes for Decks. You may choose to
                    generate three different types of Quizzes: <b>Glosses</b>, <b>Inflections</b> & <b>Sentences</b>.
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
            <p>Select the type of Quiz & adjust how you'd like for it to be generated.</p>
        </AppTip>
        <template v-if="QuizzerStore.data.scorable_type === 'deck'">
            <div class="quiz-settings-type" :class="{ 'selected': QuizzerStore.settings.quizType === 'term-gloss' }"
                 @click="QuizzerStore.settings.quizType = 'term-gloss'">
                <div>Glosses</div>
                <p><b>(Easy)</b> Test your knowledge of Arabic vocabulary with a multiple-choice Quiz, where the right
                    answer is
                    shuffled
                    with the meanings of other Terms in the Deck or Dictionary.</p>
                <AppTip v-if="QuizzerStore.data.model?.terms_count <= 5">
                    <p><b>This Deck has 5 or fewer Terms.</b> You should select
                        "All" as the decoy source to avoid unintended results (see <b>Help</b>).</p>
                </AppTip>
                <div class="quiz-settings-wrapper">
                    <ToggleDouble v-model="QuizzerStore.settings.options.allGlosses" label="decoy source"
                                  option-a="deck" option-b="all"/>
                    <ToggleDouble v-model="QuizzerStore.settings.options.anyGloss" label="any gloss" option-a="no"
                                  option-b="yes"/>
                </div>
            </div>
            <div class="quiz-settings-type"
                 :class="{ 'selected': QuizzerStore.settings.quizType === 'term-inflection' }"
                 @click="QuizzerStore.settings.quizType = 'term-inflection'">
                <div>Inflections</div>
                <p><b>(Medium)</b> Do you know your broken plurals & other forms of the Terms you've learned? Test
                    yourself with a
                    fill-in Quiz, where you have to give the correct form of the indicated Term.</p>
                <p>(You will only be quizzed on Terms with Inflections, so the Quiz may have fewer questions than
                    there are Terms.)</p>
            </div>
            <div class="quiz-settings-type" :class="{ 'selected': QuizzerStore.settings.quizType === 'sentence-term' }"
                 @click="QuizzerStore.settings.quizType = 'sentence-term'">
                <div>Sentences</div>
                <p><b>(Medium/Hard)</b> Can you find the right word to express yourself? Test yourself with a
                    multiple-choice Quiz, where you
                    have to choose the right Term to complete the Sentence.</p>
                <div class="quiz-settings-wrapper">
                    <ToggleDouble v-model="QuizzerStore.settings.options.withPrompt" label="translation"
                                  option-a="off" option-b="on"/>
                    <ToggleDouble v-model="QuizzerStore.settings.options.anyGloss" label="any gloss" option-a="no"
                                  option-b="yes"/>
                </div>
            </div>
        </template>
        <div class="window-footer">
            <button @click="QuizzerStore.startQuiz" :disabled="!QuizzerStore.settings.quizType">Start Quiz</button>
        </div>
    </QuizzerWindow>
</template>
