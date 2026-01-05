<script setup>
import {route} from 'ziggy-js';
import {computed, ref, watch} from "vue";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

let graphScores = props.model?.scores.slice(0, 10).reverse();

watch(() => props.model, (newVal) => {
    if (newVal) {
        graphScores = props.model?.scores.slice(0, 10).reverse();

    } else {
        graphScores = null;
    }
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const tooltip = ref(null);
const visible = ref(false);
const scoreData = ref(null);
const tooltipStyle = ref({
    top: "0px",
    left: "0px",
});

const showTooltip = (data, event) => {
    scoreData.value = data;
    visible.value = true;
    updatePosition(event);
}

const hideTooltip = () => {
    visible.value = false;
}

const updatePosition = (event) => {
    tooltipStyle.value = {
        top: `${event.clientY + 10}px`,
        left: `${event.clientX + 10}px`,
    };
}

const polylinePoints = computed(() => {
    if (!props.model?.scores?.length) return "";

    const scores = graphScores?.map(s => s.score);
    const width = 100;
    const height = 100;

    return scores
        ?.map((s, i) => {
            const x = (i / (scores.length - 1)) * width;
            const y = height - s * height;
            return `${x},${y}`;
        })
        .join(" ");
});

const trendLinePoints = computed(() => {
    if (!props.model?.scores?.length) return "";

    const scores = graphScores?.map(s => s.score);
    const n = scores.length;
    if (n < 2) return "";

    const xs = scores.map((_, i) => (i / (n - 1)) * 100);
    const ys = scores.map(s => 100 - s * 100);

    const xMean = xs.reduce((a, b) => a + b, 0) / n;
    const yMean = ys.reduce((a, b) => a + b, 0) / n;

    const num = xs.reduce((sum, x, i) => sum + (x - xMean) * (ys[i] - yMean), 0);
    const den = xs.reduce((sum, x) => sum + (x - xMean) ** 2, 0);
    const a = num / den;
    const b = yMean - a * xMean;

    const y0 = a * 0 + b;
    const y1 = a * 100 + b;

    return `0,${y0} 100,${y1}`;
});
</script>
<template>
    <div class="score-stats-wrapper">
        <div class="score-stats-container" :class="{ disabled: !UserStore.isStudent }">
            <div class="score-stats-container__overlay">
                You must be a Student to enable Scores.
            </div>
            <div class="score-stats-container__content">
                <div class="score-stats-highlight-wrapper">
                    <div class="score-highlight">
                        <div class="score-highlight-title">Latest Score</div>
                        <div class="featured-title">{{
                                model?.stats?.latest ? formatter.format(model?.stats.latest) : '—'
                            }}
                        </div>
                        <div v-if="model?.stats?.latest_trend" class="material-symbols-rounded"
                             :class="{'down': model?.stats?.latest_trend === 'down'}">
                            {{ model?.stats?.latest_trend === 'up' ? 'trending_up' : 'trending_down' }}
                        </div>
                    </div>
                    <div class="score-highlight">
                        <div class="score-highlight-title">Highest Score</div>
                        <div class="featured-title">{{
                                model?.stats?.highest ? formatter.format(model?.stats.highest) : '—'
                            }}
                        </div>
                        <div v-if="model?.stats?.highest_trend" class="material-symbols-rounded">trending_up</div>
                    </div>
                    <div class="score-highlight">
                        <div class="score-highlight-title">Average Score</div>
                        <div class="featured-title">{{
                                model?.stats?.average ? formatter.format(model?.stats.average) : '—'
                            }}
                        </div>
                        <div v-if="model?.stats?.average_trend" class="material-symbols-rounded"
                             :class="{'down': model?.stats?.average_trend === 'down'}">
                            {{ model?.stats?.average_trend === 'up' ? 'trending_up' : 'trending_down' }}
                        </div>
                    </div>
                </div>
                <div class="score-stats-graph-wrapper">
                    <div>
                        <div>100%</div>
                        <div>75%</div>
                        <div>50%</div>
                        <div>25%</div>
                        <div>0%</div>
                    </div>
                    <div class="score-stats-graph">
                        <svg class="line-overlay" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                             preserveAspectRatio="none">
                            <polyline class="trendline" :points="trendLinePoints"/>
                            <polyline :points="polylinePoints"/>
                        </svg>

                        <div class="score-stats-graph-item" v-for="(s, i) in graphScores" :key="i"
                             :style="{ height: `${s.score * 100}%` }"
                        >
                            <Link
                                :href="route('scores.history', { scorable_type: model.model_class, scorable_id: model.id, score: s.id })"
                                class="interact-button"
                                @mousemove="showTooltip(s, $event)"
                                @mouseleave="hideTooltip()"
                                preserve-scroll preserve-state
                            ></Link>
                        </div>

                        <div v-if="visible" :style="tooltipStyle" ref="tooltip" class="score-stats-graph-data">
                            <div style="text-transform: capitalize">{{ scoreData.settings.quizType }}</div>
                            <div>{{ formatter.format(scoreData.score) }}
                                <span style="font-size: 1.2rem">({{
                                        scoreData.results.filter(q => q.correct).length
                                    }}/{{ scoreData.results.length }})</span>
                            </div>
                            <div>{{ scoreData.created_at }}</div>
                        </div>
                    </div>
                </div>
                <div class="score-stats-detail-wrapper">
                    <div class="score-stats-detail">
                        <div>Latest Quiz</div>
                        <div>{{ model?.stats?.latest_date ?? '—' }}</div>
                    </div>
                    <div class="score-stats-detail">
                        <div>Highest Quiz</div>
                        <div>{{ model?.stats?.highest_date ?? '—' }}</div>
                    </div>
                    <div class="score-stats-detail">
                        <div>Times Quizzed</div>
                        <div>{{ model?.stats?.count ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <Link v-if="model?.stats && UserStore.isStudent" :href="route('scores.history', { scorable_type: model.model_class, scorable_id: model.id })">See
            Score History
        </Link>
    </div>
</template>
