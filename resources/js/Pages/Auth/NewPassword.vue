<script setup>
import Layout from "../../Shared/Layout.vue";
import {useForm} from "../../composables/useForm.js";
import {route} from "ziggy-js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed, ref} from "vue";

const UserStore = useUserStore();

const props = defineProps({
    token: {type: String, default: ''},
    email: {type: String, default: ''},
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
    const url = !UserStore.isUser
        ? route('password.update')
        : route('password.change');

    processing.value = true;
    clearErrors();

    try {
        if (!UserStore.isUser) {
            await axios.post(url, payload());
            window.location.href = route('homepage');
            return;
        }

        await axios.patch(url, payload());
        window.location.href = route('users.show', UserStore.user.username);

    } catch (error) {
        if (error?.response?.status === 422) {
            setErrors(error.response.data.errors ?? {});
        }
    } finally {
        processing.value = false;
    }
}

const isValidRequest = computed(() => {
    return form.password.length >= 8 && form.password_confirmation;
});

defineOptions({
    layout: Layout
})
</script>
<template>
    <Head title="New Password"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-section-head">
                <h1>{{ $t('pages.users.new-password.title') }}</h1>
            </div>
            <div class="form-body">
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
                <button @click="setNewPassword" :disabled="processing || !isValidRequest">
                    {{ $t('pages.users.new-password.set-password') }}
                </button>
            </div>
        </div>
    </div>
</template>
