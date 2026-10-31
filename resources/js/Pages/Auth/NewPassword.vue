<script setup>
import {useForm} from '../../composables/useForm.js';
import {useUserStore} from '../../stores/UserStore.js';
import {computed, ref} from 'vue';
import {useRouter} from 'vue-router';

const router = useRouter();
const UserStore = useUserStore();

const props = defineProps({
    token: {
        type: String,
        default: '',
    },
    email: {
        type: String,
        default: '',
    },
});

const {
    form,
    errors,
    clearErrors,
    setErrors,
    payload,
} = useForm({
    token: props.token || '',
    email: props.email || '',
    password: '',
    password_confirmation: '',
});
const processing = ref(false);

const setNewPassword = async () => {
    processing.value = true;
    clearErrors();

    try {
        const { data } = !UserStore.isUser
            ? await axios.post('/api/auth/reset-password', payload())
            : await axios.patch('/api/auth/change-password', payload());

        if (data.success) {
            router.push({
                name: 'users.show',
                params: { userUsername: UserStore.user.username },
            });

            Notifier.notify({
                key: 'pages.users.new-password.notifications.password-set-success',
            });
        }

    } catch (error) {
        if (error?.response?.status === 422) {
            setErrors(error.response.data.errors ?? {});
        }
        Notifier.notify({
            key: 'pages.users.new-password.notifications.password-set-failure',
        });

    } finally {
        processing.value = false;
    }
};

const isValidRequest = computed(() => {
    return form.password.length >= 8 && form.password_confirmation;
});
</script>
<template>
    <div id="app-body">
        <div class="window-container">
            <div class="window-section-head">
                <h1>{{ $t('pages.users.new-password.title') }}</h1>
            </div>

            <form @submit.prevent="setNewPassword">
                <div class="form-body">
                    <div class="field-item">
                        <label>{{ $t('user.fields.password') }}</label>
                        <div class="field-input">
                            <input type="password" autocomplete="new-password"
                                   v-model="form.password" placeholder="Lenin1917!" required>
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
                            <input type="password" autocomplete="new-password"
                                   v-model="form.password_confirmation" placeholder="Lenin1917!"
                                   required>
                            <div class="field-chars"
                                 :class="{'invalid': form.password_confirmation.length < 8}"
                                 v-text="form.password_confirmation.length + `/8`"
                            />
                        </div>
                    </div>
                </div>
                <div class="window-footer">
                    <button type="submit" :disabled="processing || !isValidRequest">
                        {{ $t('pages.users.new-password.set-password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
