<script setup>
import {useForm} from "../../composables/useForm.js";
import {computed, ref} from "vue";
import AppTip from "../AppTip.vue";
import ToggleSingle from "../ToggleSingle.vue";
import {useNotifier} from "../../composables/useNotifier.js";
import {useUserStore} from "../../stores/UserStore.js";
import {syncCsrfToken} from "../../utils/csrfToken.js";
import {useAuth} from "../../composables/useAuth.js";
import i18n from "../../i18n.js";
import {
    flushUserInterfacePreferenceSync,
    reconcileUserInterfacePreferencesWithUser,
} from "../../runtime/preferences.js";

const Notifier = useNotifier();
const UserStore = useUserStore();

const {
    startingDiscordAuth,
    startDiscordAuth,
} = useAuth();

const emit = defineEmits(['close', 'signUp']);

const {
    form: signInForm,
    errors: signInErrors,
    clearErrors: clearSignInErrors,
    setErrors: setSignInErrors,
    setRecentlySuccessful: setSignInRecentlySuccessful,
    payload: signInPayload,
} = useForm({
    email: '',
    password: '',
    remember: false,
});

const signInProcessing = ref(false);

const isValidRequest = computed(() => {
    if (!forgotPassword.value) {
        return signInForm.email.includes('@') && signInForm.email.includes('.') && signInForm.password.length;
    } else {
        return sendLinkForm.email.includes('@') && sendLinkForm.email.includes('.');
    }
});

const signIn = async () => {
    signInProcessing.value = true;
    clearSignInErrors();

    try {
        const {data} = await axios.post('/api/auth/signin', signInPayload());
        syncCsrfToken(data.csrf_token);

        UserStore.setUser(data.user);
        reconcileUserInterfacePreferencesWithUser(i18n, data.user);
        flushUserInterfacePreferenceSync(i18n, data.user).then((result) => {
            if (result.theme?.preferences) {
                UserStore.setUser({
                    ...UserStore.user,
                    preferences: result.theme.preferences,
                });
            }
        }).catch((error) => {
            console.error('Failed to sync interface preferences:', error);
        });
        setSignInRecentlySuccessful();

        emit('close');

        Notifier.notify({
            key: 'user.notifications.signed-in',
            params: {
                user: UserStore.user,
            },
        });

    } catch (error) {
        if (error?.response?.status === 422) {
            setSignInErrors(error.response.data.errors ?? {});
        }

    } finally {
        signInProcessing.value = false;
    }
};

const forgotPassword = ref(false);

const {
    form: sendLinkForm,
    errors: sendLinkErrors,
    clearErrors: clearSendLinkErrors,
    setErrors: setSendLinkErrors,
    payload: sendLinkPayload,
} = useForm({
    email: '',
});

const sendLinkProcessing = ref(false);

const sendLink = async () => {
    sendLinkProcessing.value = true;
    clearSendLinkErrors();

    try {
        const {data} = await axios.post('/api/auth/forgot-password', sendLinkPayload());

        Notifier.notify({
            key: data.status,
            type: data.success ? 'success' : 'warning',
        });

        emit('close');

    } catch (error) {
        if (error?.response?.status === 422) {
            setSendLinkErrors(error.response.data.errors ?? {});
        }

    } finally {
        sendLinkProcessing.value = false;
    }
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
                        <div v-if="signInErrors.email" v-text="signInErrors.email" class="field-error"/>
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
                        <div v-if="signInErrors.password" v-text="signInErrors.password" class="field-error"/>
                    </div>
                    <ToggleSingle v-model="signInForm.remember" :label="$t('modals.sign-in.remember-me')"/>
                </div>
                <div class="window-footer">
                    <button type="submit"
                            :disabled="signInProcessing || !isValidRequest"
                    >
                        {{ $t('modals.sign-in.submit') }}
                    </button>
                    <button
                        type="button"
                        :disabled="startingDiscordAuth"
                        @click="startDiscordAuth"
                    >
                        {{ $t('modals.sign-in.discord') }}
                    </button>
                </div>
            </form>
        </template>
        <template v-else>
            <AppTip>
                <p>{{ $t('modals.forgot-password.prompt') }}</p>
            </AppTip>
            <form @submit.prevent="sendLink">
                <div class="modal-container-body form-body">
                    <div class="field-item">
                        <label>{{ $t('user.fields.email') }}</label>
                        <div class="field-input">
                            <input type="text" v-model="sendLinkForm.email" placeholder="free@palestine.com" required>
                        </div>
                        <div v-if="sendLinkErrors.email" v-text="sendLinkErrors.email" class="field-error"/>
                    </div>
                </div>
                <div class="window-footer">
                    <button type="submit" :disabled="sendLinkProcessing || !isValidRequest">
                        {{ $t('modals.forgot-password.submit') }}
                    </button>
                </div>
            </form>
        </template>
    </div>
</template>
