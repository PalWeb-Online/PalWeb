<script setup>
import Layout from "../../../Shared/Layout.vue";
import {useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import ModalWrapper from "../../../components/ModalWrapper.vue";
import {route} from "ziggy-js";
import AppButton from "../../../components/AppButton.vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user?.name || '',
    username: props.user?.username || '',
    ar_name: props.user?.ar_name || '',
    home: props.user?.home || '',
    bio: props.user?.bio || '',
    avatar: props.user?.avatar || '',
    private: props.user?.private || false,
    dialect_id: props.user?.dialect.id || '',
});

const NotificationStore = useNotificationStore();

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return form.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    return form.name && form.username && form.ar_name;
});

const saveUser = async () => {
    isSaving.value = true;

    form.patch(route('users.update', props.user.username),
        {
            onSuccess: () => {
                NotificationStore.addNotification('The Profile has been saved!');
                form.defaults();
                isSaving.value = false;
            },
            onError: () => {
                NotificationStore.addNotification('Oh no! The Profile could not be saved.');
                isSaving.value = false;
            }
        }
    );
};

const showAvatarPicker = ref(false);
const avatars = ref([]);

onMounted(async () => {
    await axios.get(route('avatars.get')).then(response => {
        avatars.value = response.data;
    });
});

defineOptions({
    layout: Layout
});

function generateArabicName()  {
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
    <Head title="Edit Profile"/>
    <div id="app-head">
        <h1>Pal</h1>
    </div>
    <div id="app-body">
        <Link :href="route('users.show', user.username)"><- to Profile</Link>

        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <AppButton :disabled="isSaving || !hasNavigationGuard || !isValidRequest" label="Save"
                           @click="saveUser"
                />
                <AppButton :disabled="isSaving || !hasNavigationGuard" label="Reset"
                           @click="form.reset()"
                />
            </div>
        </div>

        <div class="user-container">
            <div class="action-buttons">
                <img class="toggle" :class="['lock', { public: !form.private }]"
                     :src="`/img/${form.private ? 'lock.svg' : 'lock-open.svg'}`"
                     @click="form.private = !form.private"
                     alt="Privacy"/>
            </div>
            <div class="user-item l">
                <button class="user-avatar">
                    <img :src="`/img/avatars/${form.avatar}`" @click="showAvatarPicker = true" alt="Avatar"/>
                </button>
                <div class="user-data-wrapper">
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
                    <div class="user-comment">
                        <textarea class="user-comment-content" v-model="form.bio"
                                  :placeholder="`Sadly, ${form.name} hasn't told us anything about themselves yet.`"
                        />
                        <div class="user-comment-data">
                            Joined on {{ user.created_at }} ({{ user.created_ago }}).
                        </div>
                    </div>
                    <div class="user-tag-wrapper">
                        <div class="user-tag">
                            <img class="location" src="/img/location.svg" alt="location"/>
                            <input type="text" v-model="form.home"
                                   style="background: none"
                                   placeholder="Earth, probably."
                            />
                        </div>
                        <div class="user-tag">
<!--                            todo: selecting something else causes the form to be dirty?-->
                            <img class="dialect" src="/img/mouth.svg" alt="dialect"/>
                            <select v-model="form.dialect_id">
                                <option value="1">General Palestinian</option>
                                <option value="2">Urban Palestinian</option>
                                <option value="3">Rural Palestinian</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ModalWrapper v-model="showAvatarPicker">
        <div class="theme-picker-wrapper">
            <div class="modal-heading">avatar</div>
            <div class="modal-container">
                <div class="avatar-grid">
                    <img v-for="avatar in avatars"
                         :src="`/img/avatars/${avatar}`"
                         @click="() => {form.avatar = avatar; showAvatarPicker = false}"
                         alt="Avatar"/>
                </div>
            </div>
        </div>
    </ModalWrapper>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
