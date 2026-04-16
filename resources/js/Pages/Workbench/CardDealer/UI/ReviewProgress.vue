<script setup>
import {computed, ref} from "vue";
import {route} from 'ziggy-js';
import {useUserStore} from "../../../../stores/UserStore.js";
import {useTooltip} from "../../../../composables/useTooltip.js";
import AppTooltip from "../../../../components/AppTooltip.vue";

const UserStore = useUserStore();

const props = defineProps({
    cards: Array,
    terms_count: Number
});

const appTooltip = ref(null);

const {
    isVisible,
    tooltipStyle,
    tooltipData,
    showTooltip,
    hideTooltip
} = useTooltip();

const viewMode = ref('created');

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 1,
});

const progressStats = computed(() => {
    const cardsArray = props.cards || [];

    if (cardsArray.length) {
        const activeCards = cardsArray.filter(c => c.suspended_at === null);

        const activeCount = activeCards.length;
        const reviewedCount = activeCards.filter(c => c.repetitions > 0).length;
        const suspendedCount = cardsArray.filter(c => c.suspended_at !== null).length;
        const potentialCount = props.terms_count - suspendedCount;

        const totalPoints = activeCards.reduce((acc, card) => acc + (card.mastery_score || 0), 0);

        return {
            total_created: cardsArray.length,
            total_active: activeCount,
            total_reviewed: reviewedCount,
            total_suspended: suspendedCount,
            total_potential: potentialCount,
            exposure: potentialCount > 0 ? formatter.format(reviewedCount / potentialCount) : '100%',
            progress: potentialCount > 0 ? formatter.format(totalPoints / potentialCount) : '100%',
        };

    } else {
        return null
    }
});

const masteryBarSegments = computed(() => {
    const activeCards = props.cards.filter(c => c.suspended_at === null);

    const aggregated = activeCards.reduce((acc, card) => {
        const rank = card.mastery_rank;
        if (!acc[rank]) {
            acc[rank] = {
                rank: rank,
                label: card.mastery_label,
                count: 0,
                points: 0
            };
        }
        acc[rank].count++;
        acc[rank].points += (card.mastery_score || 0);
        return acc;
    }, {});

    const barSegments = Object.values(aggregated).sort((a, b) => a.rank - b.rank)

    return barSegments.map((segment) => {
        let width = 0;

        if (viewMode.value === 'created') {
            width = (segment.count / progressStats?.value.total_potential) * 100;

        } else if (viewMode.value === 'mastery_active') {
            if (segment.rank > 0) {
                const newCount = aggregated[0]?.count || 0;
                const activePool = progressStats?.value.total_active - newCount;
                width = activePool > 0 ? (segment.count / activePool) * 100 : 0;
            }

        } else if (viewMode.value === 'mastery_total') {
            width = (segment.points / progressStats?.value.total_potential) * 100;
        }

        return {
            ...segment,
            width,
        };
    });
});
</script>
<template>
    <div class="score-stats-wrapper">
        <div class="score-stats-container" :class="{ disabled: !UserStore.isStudent }">
            <div class="score-stats-container__overlay">
                <span>You must be a Student to enable SRS Review.</span>
            </div>
            <div class="score-stats-container__content">
                <div class="score-stats-highlight-wrapper">
                    <div class="score-highlight">
                        <div class="score-highlight-title">
                            Exposure
                            <span class="material-symbols-rounded"
                                  @mousemove="appTooltip.showTooltip('% of Cards you have reviewed in the set.', $event);"
                                  @mouseleave="appTooltip.hideTooltip()"
                            >info</span>
                        </div>
                        <div class="featured-title">{{ progressStats?.exposure ?? '—' }}</div>
                    </div>
                    <div class="score-highlight">
                        <div class="score-highlight-title">
                            Progress
                            <span class="material-symbols-rounded"
                                  @mousemove="appTooltip.showTooltip('Mastery Score for the set, determined by adding up your Mastery Score for each Term.', $event);"
                                  @mouseleave="appTooltip.hideTooltip()"
                            >info</span>
                        </div>
                        <div class="featured-title">{{ progressStats?.progress ?? '—'}}</div>
                    </div>
                </div>

                <div class="score-stats-detail-wrapper">
                    <div class="progress-bar-mode-tabs">
                        <button :class="{ active: viewMode === 'created' }" @click="viewMode = 'created'">
                            Created
                        </button>
                        <button :class="{ active: viewMode === 'mastery_active' }" @click="viewMode = 'mastery_active'">
                            Mastery
                        </button>
                        <button :class="{ active: viewMode === 'mastery_total' }" @click="viewMode = 'mastery_total'">
                            Progress
                        </button>
                    </div>
                    <div class="total-progress-bar">
                        <div v-for="segment in masteryBarSegments" :key="segment.rank"
                             :class="'mastery-level__'+segment.rank"
                             :style="{ width: `${segment.width}%` }"
                             @mousemove="showTooltip(segment, $event)"
                             @mouseleave="hideTooltip()"
                        ></div>
                    </div>
                    <div class="score-stats-detail">
                        <div>Created</div>
                        <div v-if="progressStats">
                            {{ cards.length - progressStats?.total_suspended }}
                            ({{ cards.length }})
                            out of
                            {{ terms_count - progressStats?.total_suspended }}
                            ({{ terms_count }})
                            available in the set
                            ({{
                                formatter.format((cards.length - progressStats?.total_suspended) / (terms_count - progressStats?.total_suspended))
                            }})
                        </div>
                        <div v-else>—</div>
                    </div>
                    <div class="score-stats-detail">
                        <div>Reviewed</div>
                        <div v-if="progressStats">
                            {{ progressStats?.total_reviewed }}
                            out of
                            {{ progressStats?.total_active }}
                            ({{
                                progressStats?.total_active > 0
                                    ? formatter.format(progressStats?.total_reviewed / progressStats?.total_active)
                                    : '0%'
                            }}),
                            out of
                            {{ progressStats?.total_potential }} available in the set ({{ progressStats?.exposure }})
                        </div>
                        <div v-else>—</div>
                    </div>
                    <div class="score-stats-detail">
                        <div>Suspended</div>
                        <div v-if="progressStats">
                            {{ progressStats?.total_suspended }}
                            out of
                            {{ cards.length }}
                            created Cards out of
                            {{ terms_count }}
                            total Terms in the set
                        </div>
                        <div v-else>—</div>
                    </div>
                </div>
            </div>
        </div>
        <Link v-if="UserStore.isStudent" :href="route('card-dealer.cards')">Manage Cards</Link>
    </div>

    <AppTooltip ref="appTooltip"/>

    <div v-if="isVisible" :style="tooltipStyle" ref="dataTooltip" class="data-tooltip">
        <div style="text-transform: capitalize; font-weight: 700;">{{ tooltipData.label }}</div>
        <template v-if="viewMode !== 'mastery_total'">
            <div>{{ Math.round(tooltipData.width) }}%</div>
            <div>
                {{
                    tooltipData.count
                }}/{{
                    viewMode === 'created' ? progressStats?.total_potential + ' Total' : progressStats?.total_reviewed + ' Reviewed'
                }}
            </div>
        </template>
        <template v-else>
            <div>
                {{ Math.round(tooltipData.width) }}%
                <span style="font-size: 1.2rem">
                    ({{ tooltipData.count }} Cards)
                </span>
            </div>
            <div>
                {{ Number.parseFloat(tooltipData.points).toFixed(1) }}/{{ progressStats?.total_potential }} Points
            </div>
        </template>
    </div>
</template>

<style scoped lang="scss">
.total-progress-bar {
    display: flex;
    height: 3.2rem;
    border-radius: 6.4rem;
    border: 0.4rem solid white;
    background: white;
    overflow: hidden;
    margin-block-end: 1.6rem;

    & > div {
        transition: width 0.3s ease-out;
        cursor: help
    }
}

.bar-wrapper {
    display: grid;
    justify-items: center;
    text-align: center;
    font-family: var(--head-font), sans-serif;
    font-weight: 700;
    color: var(--color-dark-primary);

    .bar {
        width: 50%;
        background: var(--color-medium-primary);
        border-radius: 3.2rem 3.2rem 0.8rem 0.8rem;
    }
}
</style>
