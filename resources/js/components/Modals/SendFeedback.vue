<script setup>
import {useForm} from "../../composables/useForm.js";
import {route} from "ziggy-js";
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
    comment: ''
});
const processing = ref(false);

const isValidRequest = computed(() => {
    return form.comment.length;
});

const sendFeedback = async () => {
    processing.value = true;
    clearErrors();

    try {
        await axios.post(route('todo.store'), payload());

        emit('close');

        Notifier.notify({
            key: 'modals.send-feedback.notifications.sent-feedback',
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
            <h1>{{ $t('modals.send-feedback.title') }}</h1>
        </div>
        <form @submit.prevent="sendFeedback">
            <div class="modal-container-body form-body">
                <CommentItem :user="UserStore.user">
                    <textarea class="user-comment-content" v-model="form.comment"
                              :placeholder="$t('modals.send-feedback.placeholder')"
                    />
                    <div class="user-comment-data">
                        — {{ UserStore.user.name }} ({{ UserStore.user.username }})
                    </div>
                    <div v-if="errors.comment" v-text="errors.comment" class="field-error"/>
                </CommentItem>
            </div>
            <div class="window-footer">
                <button type="submit" :disabled="processing || !isValidRequest">
                    {{ $t('modals.send-feedback.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>
