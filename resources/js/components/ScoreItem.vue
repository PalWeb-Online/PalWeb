<script setup>
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    score: Object,
})

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const deleteScore = () => {
    if (!confirm('Are you sure you want to delete this Score?')) return;
    router.delete(route('scores.destroy', props.score.id));
}
</script>
<template>
    <div class="score-item-wrapper">
        <Link class="score-item" :href="route('scores.show', score.id)">
<!--            <div>{{ score.scorable_type }}</div>-->
            <div>{{ score.settings.typeInput ? 'Inflections' : 'Glosses' }}</div>
            <div>{{ formatter.format(score.score) }} ({{ score.results.filter(q => q.correct).length }}/{{ score.results.length }})</div>
            <div style="font-size: 1.2rem">{{ score.created_at }}</div>
        </Link>
        <button @click="deleteScore" class="material-symbols-rounded">delete</button>
    </div>
</template>
