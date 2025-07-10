<script setup>
import Layout from "../../Shared/Layout.vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    token: {type: String, default: ''},
    email: {type: String, default: ''},
});

const form = useForm({
    token: props.token || '',
    email: props.email || '',
    password: '',
    password_confirmation: '',
});

const setNewPassword = () => {
    const method = !UserStore.isUser
        ? form.post.bind(form)
        : form.patch.bind(form);

    const url = !UserStore.isUser
        ? route('password.update')
        : route('password.change');

    method(url);
}

defineOptions({
    layout: Layout
})
</script>
<template>
    <Head title="New Password"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-section-head">
                <h1>new password</h1>
            </div>
            <div class="form-body">
                <div class="field-item">
                    <label>Password</label>
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
                    <label>Confirm Password</label>
                    <div class="field-input">
                        <input type="password" v-model="form.password_confirmation" placeholder="Lenin1917!" required>
                        <div class="field-chars"
                             :class="{'invalid': form.password_confirmation.length < 8}"
                             v-text="form.password_confirmation.length + `/8`"
                        />
                    </div>
                </div>
                <button class="app-button" @click="setNewPassword" :disabled="form.processing">
                    Set Password
                </button>
            </div>
        </div>
    </div>
</template>
