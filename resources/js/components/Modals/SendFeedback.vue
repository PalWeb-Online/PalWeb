<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed} from "vue";

const emit = defineEmits(['close']);

const UserStore = useUserStore();

const form = useForm({
    comment: ''
});

const isValidRequest = computed(() => {
    return form.comment.length;
});

const sendFeedback = () => {
    form.post(route('todo.store'), {
        onSuccess: () => {
            emit('close');
        }
    });
}
</script>
<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>send feedback</h1>
        </div>
        <form @submit.prevent="sendFeedback">
            <div class="modal-container-body form-body">
                <div class="user-item m">
                    <div class="user-avatar">
                        <img :src="`/img/avatars/${UserStore.user.avatar}`" alt="Avatar"/>
                    </div>
                    <div class="user-data-wrapper">
                        <div class="user-comment">
                        <textarea class="user-comment-content" v-model="form.comment"
                                  placeholder="Is something broken? Is a word missing from the Dictionary? Let us know!"
                        />
                            <div class="user-comment-data">
                                — {{ UserStore.user.name }} ({{ UserStore.user.username }})
                            </div>
                            <div v-if="form.errors.comment" v-text="form.errors.comment" class="field-error"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="window-footer">
                <button type="submit" :disabled="form.processing || !isValidRequest">
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>
