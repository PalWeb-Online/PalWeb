<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useUserStore} from "../../stores/UserStore.js";
import {ref} from "vue";

const emit = defineEmits(['close', 'signUp']);

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const signInForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const signIn = () => {
    signInForm.post(route('signin'), {
        onSuccess: () => {
            NotificationStore.addNotification(`Welcome back, ${UserStore.user.name}!`);
            emit('close');
        }
    });
};

const forgotPassword = ref(false);

const resetLinkForm = useForm({
    email: '',
});

const sendResetLink = () => {
    resetLinkForm.post(route('password.email'), {
        onSuccess: () => {
            NotificationStore.addNotification('Password reset link has been sent to your email!');
            emit('close');
        }
    });
};
</script>
<template>
    <div class="modal-container-wrapper">
        <div class="modal-heading">sign in</div>
        <template v-if="!forgotPassword">
            <div class="modal-container form-container">
                <button @click="emit('signUp')" style="justify-self: center">New to PalWeb? Sign Up!</button>
                <div class="field-item">
                    <label>Email</label>
                    <div class="field-input">
                        <input type="text" v-model="signInForm.email" placeholder="free@palestine.com" required>
                    </div>
                    <div v-if="signInForm.errors.email" v-text="signInForm.errors.email" class="field-error"/>
                </div>
                <div class="field-item">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label>Password</label>
                        <button @click="forgotPassword = true">Forgot?</button>
                    </div>
                    <div class="field-input">
                        <input type="password" v-model="signInForm.password" placeholder="Lenin1917!" required>
                    </div>
                    <div v-if="signInForm.errors.password" v-text="signInForm.errors.password" class="field-error"/>
                </div>
                <!--            <label class="checkbox">-->
                <!--                <input type="checkbox">-->
                <!--                <span>Remember Me</span>-->
                <!--            </label>-->

                <!--            <div class="checkbox">-->
                <!--                <input type="checkbox" v-model="form.remember">Remember Me-->
                <!--            </div>-->
                <button class="app-button" @click="signIn" :disabled="signInForm.processing">
                    Sign In
                </button>
            </div>
            <a class="featured-button" :href="route('auth.discord')">Use Discord</a>
        </template>
        <template v-else>
            <div class="modal-container form-container">
                <button @click="forgotPassword = false"><- to Sign In</button>
                <p>Forgot your password? Don't worry. Write down your email address & we'll send you a link to reset
                    your password.</p>
                <div class="field-item">
                    <label>Email</label>
                    <div class="field-input">
                        <input type="text" v-model="resetLinkForm.email" placeholder="free@palestine.com" required>
                    </div>
                    <div v-if="resetLinkForm.errors.email" v-text="resetLinkForm.errors.email" class="field-error"/>
                </div>
                <button class="app-button" @click="sendResetLink" :disabled="resetLinkForm.processing">
                    Send Link
                </button>
            </div>
        </template>
    </div>
</template>
