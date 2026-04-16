<script setup>
import {Carousel, Slide} from "vue3-carousel";
import TermFlashcard from "../DeckMaster/UI/TermFlashcard.vue";
import {computed, ref} from "vue";
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import ToggleSingle from "../../../components/ToggleSingle.vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import WindowSection from "../../../components/WindowSection.vue";
import PopupWindow from "../../../components/Modals/PopupWindow.vue";
import AppTooltip from "../../../components/AppTooltip.vue";
import TermItem from "../../../components/TermItem.vue";
import AppButton from "../../../components/AppButton.vue";

const NotificationStore = useNotificationStore();

const appTooltip = ref(null);

const props = defineProps({
    deck: Object,
    cards: Array,
    options: Object
})

const queue = ref([...props.cards, {isFinalSlide: true}]);
const remainingCards = computed(() => {
    const slice = queue.value.slice(currentSlideIndex.value);

    const uniqueCards = [];
    const seenIds = new Set();

    for (let i = slice.length - 1; i >= 0; i--) {
        const card = slice[i];

        if (card.isFinalSlide) continue;

        if (!seenIds.has(card.id)) {
            uniqueCards.push(card);
            seenIds.add(card.id);
        }
    }

    return uniqueCards;
});
const processedCount = computed(() => {
    return props.cards.length - remainingCards.value.length;
});

const buckets = computed(() => {
    const learningCards = remainingCards.value.filter(c => c.step !== null).length;
    const reviewCards = remainingCards.value.filter(c => c.step === null).length;
    const newCards = remainingCards.value.filter(c => c.repetitions === 0 && c.step !== null).length;

    return {
        newCards,
        learningCards,
        reviewCards,
        total: remainingCards.value.length,
    };
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

defineOptions({
    layout: Layout
});

const carouselRef = ref(null);
const flashcardRefs = ref({});
const currentSlideIndex = ref(0);

const audioOnly = computed(() => {
    return props.options.promptType === 'audio'
})

const flipDefault = computed(() => {
    return props.options.promptType === 'gloss';
});

const flipDefaultInflections = ref(false);
const showTranscription = ref(false);

const answerShown = ref(false);
const gradeCommitted = ref(false);

const progressFill = computed(() => ({
    width: formatter.format(processedCount.value / props.cards.length),
}));

const cardStartTime = ref(Date.now());

const gradeCard = async (gradeValue) => {
    const index = currentSlideIndex.value
    const card = queue.value[index];
    if (!card) return;

    const secondsSpent = Math.floor((Date.now() - cardStartTime.value) / 1000);

    await axios.post(route('cards.update', card.id), {
        grade: gradeValue,
        seconds_spent: secondsSpent,
        next_interval: card.next_intervals[gradeValue]?.days,
        learning_steps: props.options.learningSteps
    });

    const key = `${card.id}-${index}`;
    if (flashcardRefs.value[key]) {
        flashcardRefs.value[key].flipCard();
    }

    answerShown.value = false;
    gradeCommitted.value = true;

    const staysInSession = card.step !== null || gradeValue === 0;
    const isGraduated = gradeValue >= 3 || (card.step === props.options.learningSteps - 1 && gradeValue === 2)

    if (staysInSession && !isGraduated) {
        const cardCopy = JSON.parse(JSON.stringify(card));

        if (gradeValue === 0) {
            cardCopy.step = 0;

        } else if (gradeValue === 2) {
            cardCopy.step++;
        }

        cardCopy.next_intervals = calculateLocalNextIntervals(cardCopy.step, props.options.learningSteps);

        const minInsertIndex = index + 4;
        const maxInsertIndex = queue.value.length - 1;
        const insertAt = Math.max(minInsertIndex, Math.floor(Math.random() * (maxInsertIndex - minInsertIndex + 1)) + minInsertIndex);
        queue.value.splice(insertAt, 0, cardCopy);
    }

    setTimeout(() => {
        carouselRef.value.next();
    }, 300);
}

const dismissCard = async (action) => {
    const index = currentSlideIndex.value
    const card = queue.value[index];
    if (!card) return;

    if (action === 'master') {
        await axios.post(route('cards.master', card.id));
        NotificationStore.addNotification('Mastered Card!', 'info');

    } else if (action === 'suspend') {
        await axios.post(route('cards.suspend', card.id));
        NotificationStore.addNotification('Suspended Card!', 'info');
    }

    const key = `${card.id}-${index}`;
    if (flashcardRefs.value[key]) {
        flashcardRefs.value[key].flipCard();
    }

    answerShown.value = false;
    gradeCommitted.value = true;

    setTimeout(() => {
        carouselRef.value.next();
    }, 300);
}

const calculateLocalNextIntervals = (currentStep, maxSteps) => {
    const getDays = (s) => (s < maxSteps) ? 0 : 1;
    const getLabel = (s) => (s < maxSteps) ? `step ${s}` : '1d';

    return {
        0: {days: 0, label: 'step 0'},
        1: {days: getDays(currentStep), label: getLabel(currentStep)},
        2: {days: getDays(currentStep + 1), label: getLabel(currentStep + 1)},
        3: {days: 1, label: '1d'}
    };
};

const handleSlideEnd = ({currentSlideIndex: newIndex}) => {
    currentSlideIndex.value = newIndex;
    gradeCommitted.value = false;
    cardStartTime.value = Date.now();
};
</script>

<template>
    <Head title="Card Dealer: Review"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="deck ? route('card-dealer.index', deck) : route('card-dealer.index')" class="material-symbols-rounded">
                    arrow_back
                </Link>
                <div class="window-header-url">www.palweb.app/workbench/card-dealer/review</div>
            </div>
            <div class="window-section-head">
                <h1>review</h1>
                <PopupWindow title="Card Dealer (Review)">
                    <div class="h1">Tutorial</div>
                    <p>Welcome to the Review area of the Card Dealer. Here, you will be shown <b>Owned</b> Cards that
                        are due for review or <b>New</b> Cards to begin learning. All you have to do is look at the Card
                        & see if you know the meaning of the Term. Once you're ready to check yourself, flip the Card &
                        rate your knowledge of the Term.</p>
                    <p>Cards are processed differently depending on whether they're in the Review or Learning
                        buckets.</p>
                    <div class="h2">Learning Cards</div>
                    <p>When a Card is created, it automatically starts off in the Learning bucket. At this stage, it
                        must pass a series of <b>Steps</b> before it can "graduate" to an Owned Card. Starting from Step
                        0, every time you rate the Card "Know" you increase the Step by 1 (rating it "Easy" always
                        graduates the Card, skipping the Steps entirely); rating the Card "Barely" keeps you on the same
                        Step, while rating it "Wrong" returns the Card to Step 0. Essentially, the Steps are the minimum
                        number of times you must see the Card & grade it "Know" before graduating the Card. You can
                        adjust the number of Learning Steps in the Card Dealer dashboard; the default is 3.</p>
                    <div class="h2">Review Cards</div>
                    <p>Once you're in the groove, most Cards you see in your review sessions will be in the Review
                        bucket. Unlike Learning Cards, these only require one grading Action to process. As for the
                        grading options, they will determine how soon the Card will be due again; higher ratings,
                        naturally, will make the Card be due further away in the future. However, if the Card is ever
                        graded as "Wrong", then it returns to the Learning bucket & must pass through the Learning Steps
                        again to graduate to the Review bucket. Note that none of your review stats for an Owned Card
                        will ever be lost & your Mastery Score will not necessarily revert to zero.</p>

                    <div class="h1">Options</div>
                    <div class="h2">Display Options</div>
                    <ul>
                        <li><b>Show Transcription</b> — Show Latin-script transcriptions. You may want this on more
                            often than you think, as sometimes short vowels differentiate Terms that are otherwise
                            spelled the same.
                        </li>
                    </ul>
                    <div class="h2">Card Options</div>
                    <p>When generating Cards within the Dictionary scope, it's inevitable that you will encounter Terms
                        that are trivial for you if you already have some Arabic knowledge. Also, there may be certain
                        Terms that you're completely uninterested in learning, such as potentially offensive
                        terminology. Because of that, two options are provided to skip over these situations:</p>
                    <ul>
                        <li><b>Master</b> — Set very high stats for this Card so that the Mastery Level reaches 100%.
                        </li>
                        <li><b>Suspend</b> — Set a null due date for this Card, meaning it won't be shown to you again.
                        </li>
                    </ul>
                    <p>Suspended Cards are ignored by all progress metrics, so there is no penalty for suspending
                        Cards. Also, suspended Cards do not count toward Card limits when building the review
                        session. So, if your new Card limit is set to 10 & you generated 10 new Cards today &
                        suspended 5 of them that you were not interested in, you will be able to generate 5 more
                        Cards. You may manage your suspended Cards in the Card management portal & restore them at any
                        time; restoring them will automatically make them due tomorrow.</p>
                    <p>When you select <b>Master</b>, the Card is automatically suspended, because it is assumed that
                        the Card is trivial to you & you aren't interested in seeing it in your review sessions. You may
                        restore a mastered Card if you wish, but the due date will be extremely far away.</p>
                </PopupWindow>
            </div>
            <WindowSection :visible="false">
                <template #title>
                    <h2>options</h2>
                </template>
                <template #content>
                    <div class="settings-wrapper">
                        <ToggleSingle v-model="showTranscription" label="Show Transcription"/>
                    </div>
                </template>
            </WindowSection>
            <WindowSection :visible="false">
                <template #title>
                    <h2>card</h2>
                </template>
                <template #content>
                    <div class="model-list index-list">
                        <TermItem :model="cards[currentSlideIndex].term"
                                  :glossId="cards[currentSlideIndex].term.deckPivot?.gloss_id ?? null"/>
                    </div>
                    <div class="settings-wrapper">
                        <AppButton @click="dismissCard('master')" label="master"
                                   :disabled="currentSlideIndex === queue.length - 1"
                        />
                        <AppButton @click="dismissCard('suspend')" label="suspend"
                                   :disabled="currentSlideIndex === queue.length - 1"
                        />
                    </div>
                </template>
            </WindowSection>
        </div>

        <div class="session-stats">
            <div class="progress-bar-wrapper">
                <div class="progress-bar">
                    <div class="progress-bar-fill" :style="progressFill"></div>
                </div>
                <div class="progress-bar-values">
                    <div class="featured-title s"
                         @mousemove="appTooltip.showTooltip('Processed Session Cards', $event);"
                         @mouseleave="appTooltip.hideTooltip()">
                        {{ processedCount }}
                    </div>
                    <div class="featured-title l"
                         @mousemove="appTooltip.showTooltip('Review Session Progress', $event);"
                         @mouseleave="appTooltip.hideTooltip()">
                        {{ formatter.format(processedCount / cards.length) }}
                    </div>
                    <div class="featured-title s"
                         @mousemove="appTooltip.showTooltip('Total Session Cards', $event);"
                         @mouseleave="appTooltip.hideTooltip()">
                        {{ cards.length }}
                    </div>
                </div>
            </div>
            <div class="card-buckets">
                <div class="bucket-wrapper">
                    <div class="material-symbols-rounded">package_2</div>
                    <div class="bucket-count-line"></div>
                    <div class="bucket-count-wrapper">
                        <div class="featured-title xs bucket-count"
                             @mousemove="appTooltip.showTooltip('Remaining (New) Learning Cards', $event);"
                             @mouseleave="appTooltip.hideTooltip()">
                            {{ buckets.newCards }}
                        </div>
                        <div class="featured-title s bucket-count"
                             @mousemove="appTooltip.showTooltip('Remaining Learning & Relearning Cards', $event);"
                             @mouseleave="appTooltip.hideTooltip()">
                            {{ buckets.learningCards }}
                        </div>
                        <div class="featured-title xs bucket-count"
                             @mousemove="appTooltip.showTooltip('Remaining (Owned) Relearning Cards', $event);"
                             @mouseleave="appTooltip.hideTooltip()">
                            {{ buckets.learningCards - buckets.newCards }}
                        </div>
                    </div>
                    <div class="featured-title">learn</div>
                </div>

                <div class="bucket-wrapper">
                    <div class="material-symbols-rounded">package_2</div>
                    <div class="bucket-count-wrapper">
                        <div class="featured-title s bucket-count"
                             @mousemove="appTooltip.showTooltip('Remaining Owned Cards', $event);"
                             @mouseleave="appTooltip.hideTooltip()">
                            {{ buckets.reviewCards }}
                        </div>
                    </div>
                    <div class="featured-title">review</div>
                </div>
            </div>
        </div>

        <Carousel v-if="queue.length > 0"
                  class="flashcard-carousel"
                  :enabled="false"
                  :mouse-drag="false"
                  :touch-drag="false"
                  :items-to-show="1"
                  ref="carouselRef"
                  @slide-end="handleSlideEnd"
        >
            <template #slides>
                <Slide v-for="(card, index) in queue" :key="`${card.id}-${index}`">
                    <div v-if="card.isFinalSlide">
                        <div class="featured-title l" style="text-transform: uppercase">all done!</div>
                    </div>
                    <div v-else class="term-flashcard-wrapper">
                        <TermFlashcard :ref="el => { if (el) flashcardRefs[`${card.id}-${index}`] = el }"
                                       :canInteract="!gradeCommitted && index === currentSlideIndex"
                                       :model="card.term"
                                       :audioOnly="audioOnly"
                                       :flipDefault="flipDefault"
                                       :showTerm="!flipDefault"
                                       :showTranslit="showTranscription"
                                       :flipDefaultInflections="flipDefaultInflections"
                                       @flipped="answerShown = true"
                        />

                        <div class="answer-buttons" :class="{disabled: gradeCommitted || !answerShown}">
                            <div>
                                <button class="portal-button" @click="gradeCard(0)">Wrong</button>
                                <div class="interval-preview">{{ card.next_intervals[0]?.label }}</div>
                            </div>
                            <div>
                                <button class="portal-button" @click="gradeCard(1)">Barely</button>
                                <div class="interval-preview">{{ card.next_intervals[1]?.label }}</div>
                            </div>
                            <div>
                                <button class="portal-button" @click="gradeCard(2)">Know</button>
                                <div class="interval-preview">{{ card.next_intervals[2]?.label }}</div>
                            </div>
                            <div>
                                <button class="portal-button" @click="gradeCard(3)">Easy</button>
                                <div class="interval-preview">{{ card.next_intervals[3]?.label }}</div>
                            </div>

                            <PopupWindow title="Card Dealer (Review)">
                                <div class="h1">Grading</div>
                                <p>After flipping the Card, you must indicate how well you knew the Term.</p>
                                <ul>
                                    <li><b>Wrong</b> — You didn't know the meaning of the Term or otherwise guessed
                                        incorrectly.
                                    </li>
                                    <li><b>Barely</b> — You were eventually able to produce the meaning of the Term with
                                        a lot of
                                        effort, or you guessed with a low degree of certainty; you could've easily been
                                        wrong.
                                    </li>
                                    <li><b>Know</b> — You were able to produce the meaning of the Term.</li>
                                    <li><b>Easy</b> — You immediately produced the meaning of the Term, practically
                                        automatically.
                                    </li>
                                </ul>
                            </PopupWindow>
                        </div>
                    </div>
                </Slide>
            </template>
        </Carousel>
    </div>
    <AppTooltip ref="appTooltip"/>
</template>

<style scoped lang="scss">
.session-stats {
    padding-inline: 0.8rem;
    margin-block-start: 4.8rem;
    width: min(96rem, 100%);
    justify-items: center;
    display: grid;
    gap: 3.2rem;
}

.progress-bar-wrapper {
    width: min(96rem, 100%);
    display: grid;
    grid-template-areas: 'overlap';
    align-items: center;
    user-select: none;

    & > * {
        grid-area: overlap;
    }

    .progress-bar {
        display: flex;
        background: var(--color-accent-light);
        height: 3.2rem;
        border-radius: 6.4rem;
        overflow: hidden;

        .progress-bar-fill {
            background: var(--color-medium-primary);
            transition: width 0.2s;
        }
    }

    .progress-bar-values {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        justify-items: center;

        .featured-title {
            cursor: help;
        }
    }
}

.card-buckets {
    width: min(96rem, 100%);
    display: grid;
    grid-template-columns: 1fr 1fr;
    justify-items: center;
    gap: 1.6rem;
}

.bucket-wrapper {
    display: grid;
    grid-template-areas: 'icon' 'title';
    justify-items: center;
    align-items: center;
    font-size: 3.2rem;
    user-select: none;

    .bucket-count-wrapper {
        display: flex;
        align-items: center;
        justify-items: center;
        gap: 0.75em
    }

    .bucket-count-wrapper, .bucket-count-line, .material-symbols-rounded {
        grid-area: icon;
        font-size: 1em;
    }

    .bucket-count-line {
        height: 0.4rem;
        width: 100%;
        background: var(--color-medium-secondary);
    }

    .bucket-count {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.75em;
        height: 1.75em;
        border-radius: 50%;
        font-family: var(--display-font), serif;
        font-size: 1.25em;
        color: var(--color-medium-secondary);
        background: var(--color-accent-light);
        cursor: help;

        &.xs {
            color: var(--color-accent-light);
            background: var(--color-medium-secondary);
            font-size: 0.75em;
        }
    }

    .material-symbols-rounded {
        font-size: 4em;
        color: var(--color-dark-primary);
    }

    .featured-title:not(.bucket-count) {
        grid-area: title;
        font-size: 1em;
        color: var(--color-dark-primary);
    }
}

.answer-buttons {
    display: flex;
    align-items: flex-start;
    font-size: 1.2rem;
    margin-block-start: 3em;
    gap: 2em;

    &.disabled {
        visibility: hidden;
        pointer-events: none;
    }

    & > div {
        display: grid;
        justify-items: center;
        gap: 0.8rem;
    }

    .interval-preview {
        font-family: var(--head-font), sans-serif;
        font-weight: 700;
        color: var(--color-medium-secondary);
    }

    .portal-button {
        font-size: 1em;
        background: var(--color-accent-light);
    }
}


@media (min-width: 960px) {
    .session-stats {
        padding-inline: 0;
    }

    .term-flashcard-wrapper {
        font-size: 1.6rem;
    }

    .answer-buttons .portal-button {
        font-size: 1.25em;
    }
}
</style>
