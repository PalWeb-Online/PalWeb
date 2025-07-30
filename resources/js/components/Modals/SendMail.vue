<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed} from "vue";

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
            <h1>send mail</h1>
        </div>
        <form @submit.prevent="sendMail">
            <div class="modal-container-body form-body">
                <div class="field-item">
                    <label>Subject</label>
                    <div class="field-input">
                        <input v-model="form.subject" placeholder="Subject" required>
                    </div>
                    <div v-if="form.errors.subject" v-text="form.errors.subject" class="field-error"/>
                </div>
                <div class="user-item m">
                    <div class="user-avatar">
                        <img :src="`/img/avatars/${UserStore.user.avatar}`" alt="Avatar"/>
                    </div>
                    <div class="user-data-wrapper">
                        <div class="user-comment">
                            <textarea class="user-comment-content" v-model="form.body"
                                      placeholder="What would you like to say?"
                            />
                            <div class="user-comment-data">
                                — {{ UserStore.user.name }} ({{ UserStore.user.username }})
                            </div>
                            <div v-if="form.errors.body" v-text="form.errors.body" class="field-error"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="window-footer">
                <button type="submit" :disabled="form.processing || !isValidRequest">
                    Send
                </button>
            </div>
        </form>
    </div>
</template>
