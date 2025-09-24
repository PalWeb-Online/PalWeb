<script setup>
import {useForm} from '@inertiajs/vue3';
import {route} from 'ziggy-js';

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

        <form @submit.prevent="purgeScores">
            <div class="modal-container-body form-body">
                <p style="font-weight: 700">Delete all my Scores for this model<span v-if="form.older_than">
            older than one {{ form.older_than }}</span>
                    <span v-if="form.except.length">, except my
                <span v-if="form.except.includes('latest')">latest</span>
                <span v-if="form.except.length > 1"> & </span>
                <span v-if="form.except.includes('highest')">highest</span>
                Score</span>.
                </p>

                <div class="field-item">
                    <label for="older_than">Time Limit</label>
                    <select id="older_than" v-model="form.older_than">
                        <option :value="null">None</option>
                        <option value="day">1 Day</option>
                        <option value="week">1 Week</option>
                        <option value="month">1 Month</option>
                        <option value="year">1 Year</option>
                    </select>
                </div>

                <div class="field-item">
                    <label>
                        <input type="checkbox" value="highest" v-model="form.except">
                        Keep Highest
                    </label>
                    <label>
                        <input type="checkbox" value="latest" v-model="form.except">
                        Keep Latest
                    </label>
                </div>
            </div>
            <div class="window-footer">
                <button type="submit" class="button-danger" :disabled="form.processing">purge</button>
            </div>
        </form>
    </div>
</template>
