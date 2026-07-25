<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed} from "vue";
import AppTip from "../AppTip.vue";
import {generateArabicName} from "../../utils/NameGenerator.js";

const emit = defineEmits(['close', 'signIn']);

const form = useForm({
    name: '',
    username: '',
    ar_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const isValidRequest = computed(() => {
    return Object.values(form.data()).every(value => value.length);
});

const signUp = () => {
    form.post(route('signup'), {
        onSuccess: () => {
            emit('close');
        }
    });
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
                    <div v-if="form.errors.name" v-text="form.errors.name" class="field-error"/>
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
                    <div v-if="form.errors.username" v-text="form.errors.username" class="field-error"/>
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
                    <div v-if="form.errors.ar_name" v-text="form.errors.ar_name" class="field-error"/>
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
                    <div v-if="form.errors.email" v-text="form.errors.email" class="field-error"/>
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
                    <div v-if="form.errors.password" v-text="form.errors.password" class="field-error"/>
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
                <button type="submit" :disabled="form.processing || !isValidRequest">
                    {{ $t('modals.sign-up.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>
