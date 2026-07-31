<script setup>
import {useForm} from "../../composables/useForm.js";
import {route} from "ziggy-js";
import {computed, ref} from "vue";
import AppTip from "../AppTip.vue";
import {generateArabicName} from "../../utils/NameGenerator.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useUserStore} from "../../stores/UserStore.js";
import {syncCsrfToken} from "../../utils/csrfToken.js";
import {useI18n} from "vue-i18n";
import {router} from "@inertiajs/vue3";

const {t, locale} = useI18n();
const emit = defineEmits(['close', 'signIn']);
const NotificationStore = useNotificationStore();
const UserStore = useUserStore();

const {
    form,
    errors,
    clearErrors,
    setErrors,
    setRecentlySuccessful,
    payload,
} = useForm({
    name: '',
    username: '',
    ar_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    language: computed(() => locale.value),
});

const processing = ref(false);

const isValidRequest = computed(() => {
    return Object.values(form).every(value => value.length);
});

const signUp = async () => {
    processing.value = true;
    clearErrors();

    try {
        const {data} = await axios.post(route('signup'), payload());
        syncCsrfToken(data.csrf_token);

        UserStore.setUser(data.user);
        setRecentlySuccessful();

        emit('close');

        NotificationStore.addNotification(t('signup.message', {
            user: locale.value === 'ar'
                ? UserStore.user.ar_name
                : UserStore.user.name
        }));

        router.get(route('users.show', UserStore.user.username));

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
            <h1>{{ $t('modals.sign-up.title') }}</h1>
        </div>
        <AppTip>
            <p>
                {{ $t('modals.sign-up.sign-in-prompt') }}
                <button @click="emit('signIn')">{{ $t('modals.sign-in.action') }}</button>
            </p>
        </AppTip>
        <form @submit.prevent="signUp">
            <div class="modal-container-body form-body">
                <div class="field-item">
                    <label>{{ $t('user.fields.name') }}</label>
                    <div class="field-input">
                        <input type="text" v-model="form.name" placeholder="Rafiq" required>
                        <div class="field-chars"
                             :class="{'invalid': form.name.length > 50}"
                             v-text="50 - form.name.length"
                        />
                    </div>
                    <div v-if="errors.name" v-text="errors.name" class="field-error"/>
                </div>
                <div class="field-item">
                    <label>{{ $t('user.fields.username') }}</label>
                    <div class="field-input">
                        <input type="text" v-model="form.username" placeholder="permanent.intifada" required>
                        <div class="field-chars"
                             :class="{'invalid': form.username.length > 50}"
                             v-text="50 - form.username.length"
                        />
                    </div>
                    <div v-if="errors.username" v-text="errors.username" class="field-error"/>
                </div>
                <div class="field-item">
                    <div style="display: flex; align-items: center; gap: 3.2rem;">
                        <label>{{ $t('user.fields.arabic-name') }}</label>
                        <button type="button" @click="form.ar_name = generateArabicName()">
                            {{ $t('modals.sign-up.randomize-name') }}
                        </button>
                    </div>
                    <div class="field-input">
                        <input type="text" v-model="form.ar_name" placeholder="رفيق" required>
                        <div class="field-chars"
                             :class="{'invalid': form.ar_name.length > 50}"
                             v-text="50 - form.ar_name.length"
                        />
                    </div>
                    <div v-if="errors.ar_name" v-text="errors.ar_name" class="field-error"/>
                </div>
                <div class="field-item">
                    <label>{{ $t('user.fields.email') }}</label>
                    <div class="field-input">
                        <input type="text" v-model="form.email" placeholder="free@palestine.com" required>
                        <div class="field-chars"
                             :class="{'invalid': form.email.length > 255}"
                             v-text="255 - form.email.length"
                        />
                    </div>
                    <div v-if="errors.email" v-text="errors.email" class="field-error"/>
                </div>
                <div class="field-item">
                    <label>{{ $t('user.fields.password') }}</label>
                    <div class="field-input">
                        <input type="password" v-model="form.password" placeholder="Lenin1917!" required>
                        <div class="field-chars"
                             :class="{'invalid': form.password.length < 8}"
                             v-text="form.password.length + `/8`"
                        />
                    </div>
                    <div v-if="errors.password" v-text="errors.password" class="field-error"/>
                </div>
                <div class="field-item">
                    <label>{{ $t('user.fields.confirm-password') }}</label>
                    <div class="field-input">
                        <input type="password" v-model="form.password_confirmation" placeholder="Lenin1917!" required>
                        <div class="field-chars"
                             :class="{'invalid': form.password_confirmation.length < 8}"
                             v-text="form.password_confirmation.length + `/8`"
                        />
                    </div>
                </div>
            </div>
            <div class="window-footer">
                <button type="submit" :disabled="processing || !isValidRequest">
                    {{ $t('modals.sign-up.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>
