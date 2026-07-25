<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed} from "vue";
import CommentItem from "../CommentItem.vue";

const emit = defineEmits(['close']);

const UserStore = useUserStore();

const form = useForm({
    subject: '',
    body: '',
});

const isValidRequest = computed(() => {
    return form.subject.length && form.body.length;
});

const sendMail = () => {
    form.post(route('email.store'), {
        onSuccess: () => {
            emit('close');
        }
    });
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
                    <div v-if="form.errors.subject" v-text="form.errors.subject" class="field-error"/>
                </div>
                <CommentItem :user="UserStore.user">
                    <textarea class="user-comment-content" v-model="form.body"
                              :placeholder="$t('modals.send-mail.placeholder')"
                    />
                    <div class="user-comment-data">
                        — {{ UserStore.user.name }} ({{ UserStore.user.username }})
                    </div>
                    <div v-if="form.errors.body" v-text="form.errors.body" class="field-error"/>
                </CommentItem>
            </div>
            <div class="window-footer">
                <button type="submit" :disabled="form.processing || !isValidRequest">
                    {{ $t('modals.send-mail.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>
