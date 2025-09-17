<script setup>
import {useForm} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import AppTip from "../AppTip.vue";

const props = defineProps({
    scorable_type: {type: String, required: true},
    scorable_id: {type: Number, required: true},
});

const emit = defineEmits(['close']);

const form = useForm({
    scorable_type: props.scorable_type,
    scorable_id: props.scorable_id,
    older_than: null,
    except: [],
});

const purgeScores = () => {
    if (confirm('Are you sure you want to delete these Scores? This action cannot be undone.')) {
        form.post(route('scores.purge'), {
            onSuccess: () => {
                emit('close');
            },
            onFinish: () => {
                form.processing = false
            },
        });
    }
};
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>Purge Scores</h1>
        </div>
        <AppTip>
            <p>Delete Scores for this model in bulk with this form.</p>
        </AppTip>

        <p>Delete all Scores for this model<span v-if="form.older_than"> older than one {{ form.older_than }}</span><span v-if="form.except.length">, except my [latest] Score</span>.</p>

        <form @submit.prevent="purgeScores">
            <div class="modal-container-body">
                <div style="display: flex; flex-flow: row wrap; align-items: center; gap: 0.4rem">
                    <label for="older_than" class="block text-sm font-medium">Time Limit</label>
                    <select id="older_than" v-model="form.older_than" class="mt-1 block w-full">
                        <option :value="null">None</option>
                        <option value="day">1 Day</option>
                        <option value="week">1 Week</option>
                        <option value="month">1 Month</option>
                        <option value="year">1 Year</option>
                    </select>
                    <span class="block text-sm font-medium">Except</span>
                    <label class="flex items-center"><input type="checkbox" value="highest" v-model="form.except"
                                                            class="mr-2">Highest</label>
                    <label class="flex items-center"><input type="checkbox" value="latest" v-model="form.except"
                                                            class="mr-2">Latest</label>
                </div>

                <div class="window-footer">
                    <button type="submit" class="button-danger" :disabled="form.processing">purge</button>
                </div>
            </div>
        </form>
    </div>
</template>
