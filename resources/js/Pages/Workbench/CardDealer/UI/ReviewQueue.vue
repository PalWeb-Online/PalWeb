<script setup>
import {computed} from "vue";
import {useTooltip} from "../../../../composables/useTooltip.js";

const props = defineProps({
    cards: {
        type: Array,
        default: () => [],
    }
})

const getLocalDateString = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const upcomingReviews = computed(() => {
    const schedule = [];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let i = 0; i < 30; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        const dateString = getLocalDateString(date);

        const count = props.cards.filter(card => {
            if (!card.due_at || card.suspended_at) return false;

            const cardDate = new Date(card.due_at);
            const cardDateString = getLocalDateString(cardDate);

            if (i === 0) {
                return cardDate <= new Date();
            }

            return cardDateString === dateString;
        }).length;

        schedule.push({
            date: dateString,
            label: i === 0 ? 'Today' : date.toLocaleDateString('en-US', {day: 'numeric', month: 'short'}),
            count
        });
    }

    const max = Math.max(...schedule.map(s => s.count), 1);

    return {
        data: schedule,
        max,
        legend: [max, Math.round(max * 0.75), Math.round(max * 0.5), Math.round(max * 0.25), 0]
    };
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
        <div class="featured-title s">Review Queue</div>
        <div class="review-stats-graph-wrapper">
            <div class="graph-y-axis-legend">
                <div v-for="val in upcomingReviews.legend" :key="val">{{ val }}</div>
            </div>
            <div class="review-stats-graph">
                <div class="graph-area">
                    <div v-for="day in upcomingReviews.data" :key="day.date"
                         class="bar-wrapper"
                         :style="{ height: `${(day.count / upcomingReviews.max) * 100}%` }"
                         @mousemove="showTooltip({ ...day, type: 'upcoming' }, $event)"
                         @mouseleave="hideTooltip()"
                    >
                        <div class="bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="isVisible" :style="tooltipStyle" class="data-tooltip">
        <div style="font-weight: 700">{{ tooltipData.label }}</div>
        <div>
            {{ tooltipData.count }}
            <span style="font-size: 1.2rem">Due</span>
        </div>
    </div>
</template>
