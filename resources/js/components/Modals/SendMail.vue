<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../stores/UserStore.js";

const emit = defineEmits(['close']);

const UserStore = useUserStore();

const form = useForm({
    subject: '',
    body: '',
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
    <div class="modal-container-wrapper">
        <div class="modal-heading">mail</div>
        <div class="modal-container form-container">
            <div class="field-item">
                <label>Subject</label>
                <div class="field-input">
                    <input v-model="form.subject" placeholder="Subject" required>
                </div>
                <div v-if="form.errors.subject" v-text="form.errors.subject" class="field-error"/>
            </div>
            <div class="user-item m">
                <button class="user-avatar" style="cursor: default">
                    <img :src="`/img/avatars/${UserStore.user.avatar}`" alt="Avatar"/>
                </button>
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
            <button class="app-button" @click="sendMail" :disabled="form.processing">
                Send
            </button>
        </div>
    </div>
</template>
