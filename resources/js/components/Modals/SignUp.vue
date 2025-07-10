<script setup>
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useUserStore} from "../../stores/UserStore.js";
import {computed} from "vue";
import AppTip from "../AppTip.vue";

const emit = defineEmits(['close', 'signIn']);

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const form = useForm({
    name: '',
    username: '',
    ar_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const isValidRequest = computed(() => {
    return Object.values(form).every(value => value.length);
});

const signUp = () => {
    form.post(route('signup'), {
        onSuccess: () => {
            NotificationStore.addNotification(`Glad to have you join us, ${UserStore.user.name}!`);
            emit('close');
        }
    });
}

function generateArabicName() {
    const names = [
        'محمد',
        'أحمد',
        'علي',
        'حسن',
        'حسين',
        'عبد',
        'عمر',
        'إبراهيم',
        'يوسف',
        'خالد',
        'طارق',
        'حمزة',
        'مصطفى',
        'محمود',
        'كريم',
        'أسامة',
        'عادل',
        'عمرو',
        'ناصر',
        'ياسين',
        'هشام',
        'سمير',
        'وليد',
        'موسى',
        'عماد',
        'يحيى',
        'أنس',
        'فيصل',
        'ماجد',
        'إسماعيل',
        'راشد',
        'زياد',
        'فارس',
        'جابر',
        'بلال',
        'زكريا',
        'سامر',
        'نبيل',
        'أمين',
        'طلال',
        'سعيد',
        'سليم',
        'جمال',
        'بسام',
        'فادي',
        'مروان',
        'فارس',
        'أيمن',
        'مازن',
        'رامي',
        'عزيز',
        'سليمان',
        'علاء',
        'إيهاب',
        'عماد',
        'رائد',
        'نجيب',
        'إسماعيل',
        'هاني',
        'وائل',
        'عدنان',
        'ممدوح',
        'منير',
        'نادر',
        'مراد',
        'عبد الرحمن',
        'قاسم',
        'غازي',
        'داود',
        'عاصم',
        'هاشم',
        'شادي',
        'عمار',
        'رياض',
        'فهد',
        'مختار',
        'صبري',
        'بدري',
        'فؤاد',
        'فاروق',
        'عصام',
        'زهير',
        'نواف',
        'ثامر',
        'حكيم',
        'صافي',
        'معين',
        'نزيه',
        'سهيل',
        'إياد',
        'سنان',
        'رفيق',
        'بهاء',
        'رفيق',
    ];

    const randomIndex = Math.floor(Math.random() * names.length);
    return names[randomIndex];
}
</script>
<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>sign up</h1>
        </div>
        <AppTip>
            <p>Already have an account?
                <button @click="emit('signIn')">Sign In!</button>
            </p>
        </AppTip>
        <div class="modal-container-body form-body">
            <div class="field-item">
                <label>Name</label>
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
                <label>Username</label>
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
                    <label>Arabic Name</label>
                    <button @click="form.ar_name = generateArabicName()">Randomize</button>
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
                <label>Email</label>
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
        </div>
        <div class="window-footer">
            <button @click="signUp" :disabled="form.processing || !isValidRequest">
                Sign Up
            </button>
        </div>
    </div>
</template>
