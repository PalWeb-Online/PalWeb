<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed, ref} from "vue";
import AppTip from "../AppTip.vue";
import ToggleSingle from "../ToggleSingle.vue";

const emit = defineEmits(['close', 'signUp']);

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
            <h1 v-if="!forgotPassword">{{ $t('modals.sign-in.title') }}</h1>
            <template v-else>
                <h1>{{ $t('modals.forgot-password.title') }}</h1>
                <button @click="forgotPassword = false" class="material-symbols-rounded">undo</button>
            </template>
        </div>
        <template v-if="!forgotPassword">
            <AppTip>
                <p>
                    {{ $t('modals.sign-in.sign-up-prompt') }}
                    <button @click="emit('signUp')">{{ $t('modals.sign-up.action') }}</button>
                </p>
            </AppTip>
            <form @submit.prevent="signIn">
                <div class="modal-container-body form-body">
                    <div class="field-item">
                        <label>{{ $t('user.fields.email') }}</label>
                        <div class="field-input">
                            <input type="text" v-model="signInForm.email" placeholder="free@palestine.com" required>
                        </div>
                        <div v-if="signInForm.errors.email" v-text="signInForm.errors.email" class="field-error"/>
                    </div>
                    <div class="field-item">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <label>{{ $t('user.fields.password') }}</label>
                            <button type="button" @click="forgotPassword = true">
                                {{ $t('modals.forgot-password.action') }}
                            </button>
                        </div>
                        <div class="field-input">
                            <input type="password" v-model="signInForm.password" placeholder="Lenin1917!" required>
                        </div>
                        <div v-if="signInForm.errors.password" v-text="signInForm.errors.password" class="field-error"/>
                    </div>
                    <ToggleSingle v-model="signInForm.remember" :label="$t('modals.sign-in.remember-me')"/>
                </div>
                <div class="window-footer">
                    <button type="submit" :disabled="signInForm.processing || !isValidRequest">
                        {{ $t('modals.sign-in.submit') }}
                    </button>
                    <a :href="route('auth.discord')">{{ $t('modals.sign-in.discord') }}</a>
                </div>
            </form>
        </template>
        <template v-else>
            <AppTip>
                <p>{{ $t('modals.forgot-password.prompt') }}</p>
            </AppTip>
            <form @submit.prevent="sendResetLink">
                <div class="modal-container-body form-body">
                    <div class="field-item">
                        <label>{{ $t('user.fields.email') }}</label>
                        <div class="field-input">
                            <input type="text" v-model="resetLinkForm.email" placeholder="free@palestine.com" required>
                        </div>
                        <div v-if="resetLinkForm.errors.email" v-text="resetLinkForm.errors.email" class="field-error"/>
                    </div>
                </div>
                <div class="window-footer">
                    <button type="submit" :disabled="resetLinkForm.processing || !isValidRequest">
                        {{ $t('modals.forgot-password.submit') }}
                    </button>
                </div>
            </form>
        </template>
    </div>
</template>
