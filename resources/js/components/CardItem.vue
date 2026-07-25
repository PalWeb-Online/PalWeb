 <script setup>
import {ref} from "vue";
import AppTooltip from "./AppTooltip.vue";

const props = defineProps({
    card: Object
})

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const appTooltip = ref(null);
</script>

<template>
    <div class="card-item">
        <p v-if="!card">{{ $t('card.messages.not-encountered') }}</p>

        <template v-else>
            <div class="level-icon material-symbols-rounded" :class="'mastery-level__'+card.mastery_rank"
                 @mousemove="appTooltip.showTooltip($t('card.messages.mastery-tooltip', {
                     score: formatter.format(card.mastery_score),
                     label: $t(`card.mastery-level.${card.mastery_key}`),
                     message: $t(`card.mastery-message.${card.mastery_key}`)
                 }), $event);"
                 @mouseleave="appTooltip.hideTooltip()"
            >network_intelligence
            </div>

            <div class="card-stats">
                <div>
                    <span>{{ $t('card.fields.mastery-score') }}:</span>
                    <span style="text-transform: capitalize">
                    {{ formatter.format(card.mastery_score) }}
                    ({{ $t(`card.mastery-level.${card.mastery_key}`) }})</span>
                </div>
                <div>
                    <span>{{ $t('card.fields.times-seen') }}:</span>
                    <span>{{ card.repetitions }}</span>
                </div>
                <div>
                    <span>{{ $t('card.fields.last-seen') }}:</span>
                    <span>{{ card.last_reviewed_at_human ?? $t('components.common.never') }}</span>
                </div>
                <div>
                    <span>{{ $t('card.fields.due-on') }}:</span>
                    <span>{{ card.due_at_human ?? $t('components.common.never') }}</span>
                </div>
            </div>
        </template>
    </div>
    <AppTooltip ref="appTooltip"/>
</template>

<style scoped lang="scss">
.term-container-head .card-item {
    font-size: 2.8rem;
}

.card-item {
    display: flex;
    align-items: center;
    color: var(--color-dark-primary);
    background: var(--color-pastel-light);
    font-size: 2.4rem;
    gap: 0.25em;

    p {
        margin: 0.5em 1em;
        font-size: 0.5em;
    }

    .level-icon.material-symbols-rounded {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgb(255 255 255 / 0.9);
        font-size: 0.75em;
        width: 1.25em;
        height: 1.25em;
        border-radius: 50%;
        margin: 0.25em;
        cursor: help;
    }

    & > button.material-symbols-rounded {
        width: auto;
        font-size: 0.75em;
        margin: 0.25em;
        color: var(--color-medium-primary);
    }

    .card-stats {
        flex-grow: 1;
        display: flex;
        flex-flow: row wrap;
        font-family: inherit;
        align-items: center;
        font-size: 0.5em;
        padding-block: 0.5em;
        gap: 0.25em 0.75em;

        & > div {
            display: flex;
            flex-flow: row wrap;
            align-items: center;
            gap: 0.5em;

            & > *:first-child {
                font-weight: 700;
            }
        }

        .material-symbols-rounded {
            width: auto;
            font-size: 1.5em;
            color: var(--color-medium-primary);
        }
    }
}
</style>
