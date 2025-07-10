<script setup>
import Layout from "../../../Shared/Layout.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import {route} from "ziggy-js";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";

const props = defineProps({
    user: Object,
});

const NotificationStore = useNotificationStore();

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
                form.defaults();
            },
            onError: () => {
                NotificationStore.addNotification('Oh no! The Profile could not be saved.', 'error');
            },
            onFinish: () => {
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
    <Head title="Edit Profile"/>
    <div id="app-body">
        <div class="window-container">
            <div class="action-buttons">
            </div>
            <div class="window-header">
                <Link class="material-symbols-rounded" :href="route('users.show', user.username)">arrow_back</Link>
                <button @click="form.private = !form.private" class="material-symbols-rounded">
                    {{ form.private ? 'lock' : 'public' }}
                </button>
                <div class="window-header-url">www.palweb.app/hub/users/{user}</div>
                <button :disabled="isSaving || !hasNavigationGuard || !isValidRequest" @click="saveUser"
                        class="material-symbols-rounded">
                    save
                </button>
                <button :disabled="isSaving || !hasNavigationGuard" @click="form.reset()"
                        class="material-symbols-rounded">
                    undo
                </button>
            </div>
            <div class="window-section-head">
                <h1>profile</h1>
                <Link :href="route('users.show', user.username)" class="material-symbols-rounded">visibility</Link>
            </div>
            <div class="user-item l">
                <button class="user-avatar">
                    <img :src="`/img/avatars/${form.avatar}`" @click="showAvatarPicker = true" alt="Avatar"/>
                </button>
                <div class="user-data-wrapper">
                    <div class="form-body">
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
                    </div>
                    <div class="user-comment">
                        <textarea class="user-comment-content" v-model="form.bio"
                                  :placeholder="`Sadly, ${form.name} hasn't told us anything about themselves yet.`"
                        />
                        <div class="user-comment-data">
                            Joined on {{ user.created_at }} ({{ user.created_ago }}).
                        </div>
                        <div v-if="form.errors.bio" v-text="form.errors.bio" class="field-error"/>
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
                            <img class="dialect" src="/img/mouth.svg" alt="dialect"/>
                            <select v-model="form.dialect_id">
                                <option :value="8">Central Urban Palestinian</option>
                                <option :value="9">Northern Urban Palestinian</option>
                                <option :value="10">Central Rural Palestinian</option>
                                <option :value="11">Northern Rural Palestinian</option>
                                <option :value="6">Palestinian Bedouin</option>
                                <option :value="7">Palestinian Druze</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ModalWrapper v-model="showAvatarPicker">
        <div class="window-container modal-container">
            <div class="window-section-head">
                <h1>avatar</h1>
            </div>
            <div class="modal-container-body">
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
