<script setup>
import {route} from 'ziggy-js';

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});
</script>
<template>
    <div class="score-stats-wrapper">
        <div class="score-stats-container">
            <div class="score-stats-highlight-wrapper">
                <div class="score-highlight">
                    <div>Latest Score</div>
                    <div class="featured-title">{{ model?.stats?.latest ? formatter.format(model?.stats.latest) : '—' }}</div>
                </div>
                <div class="score-highlight">
                    <div>Highest Score</div>
                    <div class="featured-title">{{ model?.stats?.highest ? formatter.format(model?.stats.highest) : '—' }}</div>
                </div>
                <div class="score-highlight">
                    <div>Average Score</div>
                    <div class="featured-title">{{ model?.stats?.average ? formatter.format(model?.stats.average) : '—' }}</div>
                </div>
            </div>
            <div class="score-stats-detail-wrapper">
                <div class="score-stats-detail">
                    <div>Latest Quiz</div>
                    <div>{{ model?.stats?.latest_date ?? '—' }}</div>
                </div>
                <div class="score-stats-detail">
                    <div>Times Quizzed</div>
                    <div>{{ model?.stats?.count ?? '—' }}</div>
                </div>
            </div>
        </div>
        <Link v-if="model?.stats" :href="route('scores.history.deck', model.id)">See Score History</Link>
    </div>
</template>
