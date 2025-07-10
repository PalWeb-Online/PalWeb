<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed, ref} from "vue";
import AppTip from "../AppTip.vue";
import AppButton from "../AppButton.vue";

const emit = defineEmits(['close', 'signUp']);

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const signInForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const isValidRequest = computed(() => {
    if (!forgotPassword.value) {
        return signInForm.email.includes('@') && signInForm.email.includes('.') && signInForm.password.length;
    } else {
        return resetLinkForm.email.includes('@') && resetLinkForm.email.includes('.');
    }
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
            emit('close');
        }
    });
};
</script>
<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1 v-if="!forgotPassword">sign in</h1>
            <template v-else>
                <h1>forgot password</h1>
                <button @click="forgotPassword = false" class="material-symbols-rounded">undo</button>
            </template>
        </div>
        <template v-if="!forgotPassword">
            <AppTip>
                <p>New to PalWeb? <button @click="emit('signUp')">Sign Up!</button></p>
            </AppTip>
            <div class="modal-container-body form-body">
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
                <div class="field-toggle-wrapper">
                    <button class="field-toggle" :class="{ active: signInForm.remember }"
                            @click="signInForm.remember = !signInForm.remember">
                        <div class="field-toggle-slider"></div>
                    </button>
                    <div>remember me</div>
                </div>
            </div>
            <div class="window-footer">
                <button @click="signIn" :disabled="signInForm.processing || !isValidRequest">Sign In</button>
                <a :href="route('auth.discord')">Use Discord</a>
            </div>
        </template>
        <template v-else>
            <AppTip>
                <p>No password? No problem. Write down your email address & I'll send you a reset link.</p>
            </AppTip>
            <div class="modal-container-body form-body">
                <div class="field-item">
                    <label>Email</label>
                    <div class="field-input">
                        <input type="text" v-model="resetLinkForm.email" placeholder="free@palestine.com" required>
                    </div>
                    <div v-if="resetLinkForm.errors.email" v-text="resetLinkForm.errors.email" class="field-error"/>
                </div>
            </div>
            <div class="window-footer">
                <button @click="sendResetLink" :disabled="resetLinkForm.processing || !isValidRequest">
                    Send Link
                </button>
            </div>
        </template>
    </div>
</template>
