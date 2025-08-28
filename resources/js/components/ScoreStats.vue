<script setup>
const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});
</script>
<template>
    <div class="score-stats-wrapper">
        <div class="score-stats-container">
            <div class="score-stats-highlight-wrapper">
                <div class="score-highlight">
                    <div>Latest Score</div>
                    <div class="featured-title">{{ model ? '80%' : '—' }}</div>
                </div>
                <div class="score-highlight">
                    <div>Highest Score</div>
                    <div class="featured-title">{{ model ? '90%' : '—' }}</div>
                </div>
                <div class="score-highlight">
                    <div>Average Score</div>
                    <div class="featured-title">{{ model ? '85%' : '—' }}</div>
                </div>
            </div>
            <div class="score-stats-detail-wrapper">
                <div class="score-stats-detail">
                    <div>Latest Quiz</div>
                    <div>{{ model ? '01/01/2025' : '—' }}</div>
                </div>
                <div class="score-stats-detail">
                    <div>Times Quizzed</div>
                    <div>{{ model ? '3' : '—' }}</div>
                </div>
            </div>
        </div>
        <a v-if="model">See Score History</a>
    </div>
</template>
