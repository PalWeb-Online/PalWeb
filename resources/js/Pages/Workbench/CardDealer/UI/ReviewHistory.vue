<script setup>
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import {useTooltip} from "../../../../composables/useTooltip.js";
import {computed, ref} from "vue";

const props = defineProps({
    review_history: Object
})

const getLocalDateString = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const formatTime = (seconds) => {
    if (seconds < 60) return `${seconds}s`;
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return secs > 0 ? `${mins}m ${secs}s` : `${mins}m`;
};

const historyGraphMode = ref('cards');

const reviewHistory = computed(() => {
    const history = [];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let i = 29; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        const dateString = getLocalDateString(date);

        const dayData = props.review_history[dateString] || null;

        const newCardsCount = parseInt(dayData?.new_cards) || 0;
        const ownedCardsCount = parseInt(dayData?.owned_cards) || 0;
        const rightReviewsCount = parseInt(dayData?.right_reviews) || 0;
        const wrongReviewsCount = parseInt(dayData?.wrong_reviews) || 0;
        const learningStepsCount = parseInt(dayData?.learning_steps) || 0;
        const relearningStepsCount = parseInt(dayData?.relearning_steps) || 0;

        history.push({
            date: dateString,
            label: i === 0 ? 'Today' : date.toLocaleDateString('en-US', {day: 'numeric', month: 'short'}),
            newCardsCount,
            ownedCardsCount,
            rightReviewsCount,
            wrongReviewsCount,
            learningStepsCount,
            relearningStepsCount,
            totalCards: newCardsCount + ownedCardsCount,
            totalActions: rightReviewsCount + wrongReviewsCount + learningStepsCount + relearningStepsCount,
            totalSeconds: parseInt(dayData?.total_seconds) || 0
        });
    }

    const max = Math.max(...history.map(h => h.totalActions), 1);

    return {
        data: history,
        max,
        legend: [max, Math.round(max * 0.75), Math.round(max * 0.5), Math.round(max * 0.25), 0]
    };
});

const averageLineY = computed(() => {
    const history = reviewHistory.value.data;
    if (!history.length) return 0;

    const avg = history.reduce((acc, curr) => acc + curr.totalActions, 0) / history.length;
    return 100 - (avg / reviewHistory.value.max) * 100;
});

const trendLinePoints = computed(() => {
    const history = reviewHistory.value.data;
    const n = history.length;
    if (n < 2) return "";

    const max = reviewHistory.value.max;
    const ys = history.map(h => 100 - (h.totalActions / max) * 100);
    const xs = history.map((_, i) => (i / (n - 1)) * 100);

    const xMean = xs.reduce((a, b) => a + b, 0) / n;
    const yMean = ys.reduce((a, b) => a + b, 0) / n;

    const num = xs.reduce((sum, x, i) => sum + (x - xMean) * (ys[i] - yMean), 0);
    const den = xs.reduce((sum, x) => sum + (x - xMean) ** 2, 0);

    if (den === 0) return `0,${yMean} 100,${yMean}`;

    const a = num / den;
    const b = yMean - a * xMean;

    const y0 = a * 0 + b;
    const y1 = a * 100 + b;

    return `0,${y0} 100,${y1}`;
});

const {
    isVisible,
    tooltipStyle,
    tooltipData,
    showTooltip,
    hideTooltip
} = useTooltip();
</script>

<template>
    <div class="chart-section">
        <div class="featured-title s">
            Review History
            <PopupWindow title="Card Dealer (Stats)">
                <div class="h1">Review History</div>
                <div class="h2">Cards</div>
                <p>The Cards tab displays the total count of <b>New</b> & <b>Owned</b> Cards processed
                    per
                    day. <b>New</b> Cards are those that were graded for the first time that day.
                    <b>Owned</b> Cards are those that have ever graduated from the Learning stage, even
                    those that have been demoted due to a "Wrong" grade. Due Cards that were not graded
                    on a given day (e.g. if the Total Cards limit was reached) are not counted, nor are
                    Cards that were created that day but never graded.</p>
                <ul>
                    <li>Cards</li>
                    <ul>
                        <li>Owned</li>
                        <li>New</li>
                    </ul>
                </ul>
                <div class="h2">Actions</div>
                <p>The Actions tab displays the total count of Actions (i.e. instances of grading a
                    Card)
                    per day, split into <b>Reviews</b> & <b>Steps</b>. <b>Steps</b> are the series of
                    correct Actions required to graduate a Card from the Learning stage. Unless the
                    Learning
                    Steps setting is set to 1, by definition there will be multiple <b>Steps</b> per
                    processed Learning Card. <b>Reviews</b> are the typical grading Actions taken on <b>Owned</b>
                    Cards; they are done only once per Card. However, if the selected grade is "Wrong",
                    the
                    Owned Card will be demoted to a Relearning Card; while it remains an Owned Card, it
                    will have to go through the necessary graduation <b>Steps</b> again.</p>
                <ul>
                    <li>Actions</li>
                    <ul>
                        <li>Reviews</li>
                        <ul>
                            <li>Right</li>
                            <li>Wrong</li>
                        </ul>
                        <li>Steps</li>
                        <ul>
                            <li>Learning</li>
                            <li>Relearning</li>
                        </ul>
                    </ul>
                </ul>
                <div class="h2">Trends</div>
                <p>The Trends tab displays the average Actions per day & how the count of daily Actions
                    is
                    trending over time.</p>
            </PopupWindow>
        </div>
        <div class="progress-bar-mode-tabs">
            <button :class="{ active: historyGraphMode === 'cards' }"
                    @click="historyGraphMode = 'cards'">
                Cards
            </button>
            <button :class="{ active: historyGraphMode === 'actions' }"
                    @click="historyGraphMode = 'actions'">
                Actions
            </button>
            <button :class="{ active: historyGraphMode === 'trends' }"
                    @click="historyGraphMode = 'trends'">
                Trends
            </button>
        </div>
        <div class="review-stats-graph-wrapper">
            <div class="graph-y-axis-legend">
                <div v-for="val in reviewHistory.legend" :key="val">{{ val }}</div>
            </div>
            <div class="review-stats-graph" :class="`mode__${historyGraphMode}`">
                <svg class="line-overlay" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                     preserveAspectRatio="none">
                    <line class="average-line" x1="0" :y1="averageLineY" x2="100"
                          :y2="averageLineY"/>
                    <polyline class="trendline" :points="trendLinePoints"/>
                </svg>
                <div class="graph-area">
                    <div class="history-bars-wrapper" v-for="day in reviewHistory.data"
                         :key="day.date">
                        <div class="bar-wrapper reviews"
                             :style="{ height: `${(day.totalActions / reviewHistory.max) * 100}%` }"
                             @mousemove="showTooltip({ ...day, type: 'history' }, $event)"
                             @mouseleave="hideTooltip()"
                        >
                            <div class="bar-stack">
                                <div class="bar review-bar"
                                     :style="{ height: `${(day.rightReviewsCount / day.totalActions) * 100}%` }"></div>
                                <div class="bar lapses-bar"
                                     :style="{ height: `${(day.wrongReviewsCount / day.totalActions) * 100}%` }"></div>
                                <div class="bar relearning-bar"
                                     :style="{ height: `${(day.relearningStepsCount / day.totalActions) * 100}%` }"></div>
                                <div class="bar new-bar"
                                     :style="{ height: `${(day.learningStepsCount / day.totalActions) * 100}%` }"></div>
                            </div>
                        </div>
                        <div class="bar-wrapper cards"
                             :style="{ height: `${(day.totalCards / reviewHistory.max) * 100}%` }"
                             @mousemove="showTooltip({ ...day, type: 'history' }, $event)"
                             @mouseleave="hideTooltip()"
                        >
                            <div class="bar-stack">
                                <div class="bar review-bar"
                                     :style="{ height: `${(day.ownedCardsCount / day.totalCards) * 100}%` }"></div>
                                <div class="bar new-bar"
                                     :style="{ height: `${(day.newCardsCount / day.totalCards) * 100}%` }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="isVisible" :style="tooltipStyle" class="data-tooltip">
        <div style="font-weight: 700">{{ tooltipData.label }}</div>
        <template v-if="historyGraphMode === 'cards'">
            <div>{{ tooltipData.totalCards }}
                <span style="font-size: 1.2rem">
                    Cards
                </span>
            </div>
            <div style="display: grid; gap: 0.2rem">
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="bar-legend" style="background: var(--color-dark-primary)"></div>
                    {{ tooltipData.ownedCardsCount }} Owned
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="bar-legend" style="background: var(--color-pastel-dark)"></div>
                    {{ tooltipData.newCardsCount }} New
                </div>
            </div>
        </template>
        <template v-else>
            <div>{{ tooltipData.totalActions }}
                <span style="font-size: 1.2rem">
                        Actions
                         <span style="font-weight: 400">
                        ({{ formatTime(tooltipData.totalSeconds) }})
                    </span>
                    </span>
            </div>
            <div style="display: grid; gap: 0.2rem">
                <div style="font-weight: 700">
                    {{ tooltipData.rightReviewsCount + tooltipData.wrongReviewsCount }}
                    Reviews
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="bar-legend" style="background: var(--color-dark-primary)"></div>
                    {{ tooltipData.rightReviewsCount }} Right
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="bar-legend" style="background: var(--color-medium-primary)"></div>
                    {{ tooltipData.wrongReviewsCount }} Wrong
                </div>
            </div>
            <div style="display: grid; gap: 0.2rem">
                <div style="font-weight: 700">
                    {{ tooltipData.relearningStepsCount + tooltipData.learningStepsCount }}
                    Steps
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="bar-legend" style="background: var(--color-accent-light)"></div>
                    {{ tooltipData.relearningStepsCount }} Relearning
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="bar-legend" style="background: var(--color-pastel-dark)"></div>
                    {{ tooltipData.learningStepsCount }} Learning
                </div>
            </div>
        </template>
    </div>
</template>
