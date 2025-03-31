<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useUserStore} from "../../stores/UserStore.js";

const emit = defineEmits(['close']);

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const form = useForm({
    feedback: ''
});

const sendFeedback = () => {
    form.post(route('todo.store'), {
        onSuccess: () => {
            NotificationStore.addNotification('Thank you for your feedback!');
            emit('close');
        }
    });
}
</script>
<template>
    <div class="modal-container-wrapper">
        <div class="modal-heading">feedback</div>
        <div class="modal-container form-container">
            <div class="user-item m">
                <button class="user-avatar" style="cursor: default">
                    <img :src="`/img/avatars/${UserStore.user.avatar}`" alt="Avatar"/>
                </button>
                <div class="user-data-wrapper">
                    <div class="user-comment">
                        <textarea class="user-comment-content" v-model="form.feedback"
                                  placeholder="Is something broken? Is a word missing from the Dictionary? Let us know!"
                        />
                        <div class="user-comment-data">
                            — {{ UserStore.user.name }} ({{ UserStore.user.username }})
                        </div>
                        <div v-if="form.errors.feedback" v-text="form.errors.feedback" class="field-error"/>
                    </div>
                </div>
            </div>
            <button class="app-button" @click="sendFeedback" :disabled="form.processing">
                Submit
            </button>
        </div>
    </div>
</template>
