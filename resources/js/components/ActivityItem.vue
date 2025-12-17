<script setup>
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import ActivityActions from "./Actions/ActivityActions.vue";

const UserStore = useUserStore();

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
                 @click="router.get(route('activities.show', model.lesson.slug))">
                <div class="model-item-title">
                    {{ model.title }}
                </div>
            </div>
            <ActivityActions v-if="UserStore.isAdmin" :model="model"/>
        </div>

        <div v-if="model.stats" class="model-item-stats">
            <span style="font-weight: 700">Times Quizzed</span>
            <span>{{ model.stats.count }}</span>
            ·
            <span style="font-weight: 700">Latest Score</span>
            <span>{{ formatter.format(model.stats.latest) }}</span>
            <span>({{ model.stats.latest_date }})</span>
        </div>
    </div>
</template>
