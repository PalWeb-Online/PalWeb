<script setup>
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import ActivityActions from "./Actions/ActivityActions.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});
</script>

<template>
    <div class="model-item-container deck-item-container">
        <div class="model-item deck-item">
            <div class="model-item-content" style="border: none"
                 @click="router.get(route('scores.history', { scorable_type: 'activity', scorable_id: model.id }))">
                <div class="model-item-title">
                    {{ model.title }}
                </div>
            </div>
            <ActivityActions :model="model"/>
        </div>

        <div v-if="model.stats" class="model-item-stats">
            <span style="font-weight: 700">{{ $t('components.score.stats.times-quizzed') }}</span>
            <span>{{ model.stats.count }}</span>
            ·
            <span style="font-weight: 700">{{ $t('components.score.stats.latest-score') }}</span>
            <span>{{ formatter.format(model.stats.latest) }}</span>
            <span>({{ model.stats.latest_date }})</span>
        </div>
    </div>
</template>
