<script setup>
import {computed, reactive} from 'vue';
import {router} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import {useI18n} from "vue-i18n";
import {useNotificationStore} from "../../stores/NotificationStore.js";

const {t, locale} = useI18n();
const NotificationStore = useNotificationStore();

const props = defineProps({
    scorable_type: {type: String, required: true},
    scorable_id: {type: Number, required: true},
});

const emit = defineEmits(['close']);

const form = reactive({
    scorable_type: props.scorable_type,
    scorable_id: props.scorable_id,
    older_than: null,
    except: [],
    processing: false,
});

const formattedExceptions = computed(() => {
    const labels = form.except.map((value) =>
        t(`modals.purge-scores.exceptions.${value}`)
    )

    return new Intl.ListFormat(locale.value, {
        style: 'long',
        type: 'conjunction',
    }).format(labels)
})

const purgeScores = async () => {
    if (!confirm(t('modals.purge-scores.notifications.purge-confirm'))) return;

    form.processing = true;

    try {
        const {data} = await axios.post(route('scores.purge'), {
            scorable_type: form.scorable_type,
            scorable_id: form.scorable_id,
            older_than: form.older_than,
            except: form.except,
        });

        if (data.success) {
            NotificationStore.addNotification(t('modals.purge-scores.notifications.purge-success', {count: data.count}));
            emit('close');
            router.reload();
        }
    } finally {
        form.processing = false;
    }
};
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>{{ $t('modals.purge-scores.title') }}</h1>
        </div>

        <form @submit.prevent="purgeScores">
            <div class="modal-container-body form-body">
                <div class="purge-message">
                    <p>{{ $t('modals.purge-scores.message') }}</p>
                    <ul v-if="form.older_than || form.except.length">
                        <li v-if="form.older_than">
                            {{ $t('modals.purge-scores.older-than', {age: t(`modals.purge-scores.time-limits.${form.older_than}`)}) }}
                        </li>
                        <li v-if="form.except.length">
                            {{ $t('modals.purge-scores.except-for', {scores: formattedExceptions}) }}
                        </li>
                    </ul>
                </div>

                <div class="field-item">
                    <label for="older_than">{{ $t('modals.purge-scores.fields.time-limit') }}</label>
                    <select id="older_than" v-model="form.older_than">
                        <option :value="null">{{ $t('modals.purge-scores.time-limits.none') }}</option>
                        <option value="day">{{ $t('modals.purge-scores.time-limits.day') }}</option>
                        <option value="week">{{ $t('modals.purge-scores.time-limits.week') }}</option>
                        <option value="month">{{ $t('modals.purge-scores.time-limits.month') }}</option>
                        <option value="year">{{ $t('modals.purge-scores.time-limits.year') }}</option>
                    </select>
                </div>

                <div class="field-item">
                    <label>{{ $t('modals.purge-scores.fields.keep') }}</label>
                    <label>
                        <input type="checkbox" value="highest" v-model="form.except">
                        {{ $t('modals.purge-scores.exceptions.highest') }}
                    </label>
                    <label>
                        <input type="checkbox" value="latest" v-model="form.except">
                        {{ $t('modals.purge-scores.exceptions.latest') }}
                    </label>
                </div>
            </div>
            <div class="window-footer">
                <button type="submit" class="button-danger" :disabled="form.processing">
                    {{ $t('modals.purge-scores.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped lang="scss">
.purge-message {
    display: grid;
    gap: 0.6rem;
    background: var(--color-accent-light);
    border-radius: 0.8rem;
    padding: 1.6rem 2.0rem;
    font-family: var(--body-font);
    font-weight: 700;
    text-transform: none;
    margin: 0;

    p, ul {
        margin: 0;
        font-size: 1.6rem;
    }
}

.form-body .field-item label:has(input) {
    text-transform: none;
}
</style>
