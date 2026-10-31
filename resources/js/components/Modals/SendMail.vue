<script setup>
import {useForm} from "../../composables/useForm.js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed, ref} from "vue";
import CommentItem from "../CommentItem.vue";
import {useNotifier} from '../../composables/useNotifier.js';

const emit = defineEmits(['close']);

const UserStore = useUserStore();
const Notifier = useNotifier();

const {
    form,
    errors,
    clearErrors,
    setErrors,
    payload,
} = useForm({
    subject: '',
    body: '',
});

const processing = ref(false);

const isValidRequest = computed(() => {
    return form.subject.length && form.body.length;
});

const sendMail = async () => {
    processing.value = true;
    clearErrors();

    try {
        await axios.post('/api/admin/email', payload());

        emit('close');

        Notifier.notify({
            key: 'modals.send-mail.notifications.mail-queued',
        });

    } catch (error) {
        if (error?.response?.status === 422) {
            setErrors(error.response.data.errors ?? {});
        }
    } finally {
        processing.value = false;
    }
}
</script>
<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>{{ $t('modals.send-mail.title') }}</h1>
        </div>
        <form @submit.prevent="sendMail">
            <div class="modal-container-body form-body">
                <div class="field-item">
                    <label>{{ $t('modals.send-mail.fields.subject') }}</label>
                    <div class="field-input">
                        <input v-model="form.subject" :placeholder="$t('modals.send-mail.fields.subject')" required>
                    </div>
                    <div v-if="errors.subject" v-text="errors.subject" class="field-error"/>
                </div>
                <CommentItem :user="UserStore.user">
                    <textarea class="user-comment-content" v-model="form.body"
                              :placeholder="$t('modals.send-mail.placeholder')"
                    />
                    <div class="user-comment-data">
                        — {{ UserStore.user.name }} ({{ UserStore.user.username }})
                    </div>
                    <div v-if="errors.body" v-text="errors.body" class="field-error"/>
                </CommentItem>
            </div>
            <div class="window-footer">
                <button type="submit" :disabled="processing || !isValidRequest">
                    {{ $t('modals.send-mail.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>
