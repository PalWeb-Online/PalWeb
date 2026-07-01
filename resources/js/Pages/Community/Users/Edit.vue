<script setup>
import Layout from "../../../Shared/Layout.vue";
import {Link} from "@inertiajs/vue3";
import {computed, onMounted, ref, watch} from "vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import {route} from "ziggy-js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import UserAvatarWrapper from "../../../components/UserAvatarWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useUserEditor} from "../../../composables/users/useUserEditor.js";
import {useTeacherEditor} from "../../../composables/teachers/useTeacherEditor.js";
import {generateArabicName} from "../../../utils/NameGenerator.js";
import {useUser} from "../../../composables/users/useUser.js";
import {useUserStore} from "../../../stores/UserStore.js";
import {useUserValidation} from "../../../composables/users/useUserValidation.js";
import AvatarPicker from "../../../components/AvatarPicker.vue";

const props = defineProps({
    userId: {
        type: Number,
        required: true,
    },
    username: {
        type: String,
        required: true,
    },
});

const UserStore = useUserStore();
const {isStudent} = useUser(computed(() => user.value));

const {
    form: userForm,
    errors: userBackendErrors,
    isDirty: isUserDirty,
    reset: resetUser,
    isSaving: isSavingUser,
    isLoadingForm: isLoadingUserForm,
    loadForm: loadUserForm,
    saveUser,
    user,
    userNotFound,
} = useUserEditor({
    userId: computed(() => props.userId),
    username: computed(() => props.username),
});

const {
    form: teacherForm,
    errors: teacherBackendErrors,
    isDirty: isTeacherDirty,
    reset: resetTeacher,
    isSaving: isSavingTeacher,
    isDeleting: isDeletingTeacher,
// isLoadingTeacherForm is possible but pointless because loading simply means instantaneously mounting `user.teacher`;
    loadForm: loadTeacherForm,
    saveTeacher,
    deleteTeacher,
    clearTeacherForm,
    teacherExists,
} = useTeacherEditor({
    user,
});

const hasNavigationGuard = computed(() => isUserDirty.value || isTeacherDirty.value);

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const {
    userErrors,
    teacherErrors,
    isValidUserRequest,
    isValidTeacherRequest,
} = useUserValidation({
    userForm,
    userBackendErrors,
    teacherForm,
    teacherBackendErrors,
});

const showTeacherForm = ref(false);

const canCreateTeacher = computed(() => {
    return isStudent.value && UserStore.isAdmin
});

const canManageTeacher = computed(() => {
    return teacherExists.value || canCreateTeacher.value;
});

const teacherFormExists = computed(() => {
    return teacherExists.value || showTeacherForm.value;
});

const showAvatarPicker = ref(false);
const uploadedAvatars = ref([]);

const selectedAvatarUrl = computed(() => {
    if (userForm.avatar_id) {
        const uploadedAvatar = uploadedAvatars.value.find((avatar) => avatar.id === userForm.avatar_id);

        if (uploadedAvatar) {
            return uploadedAvatar.url;
        }
    }

    if (userForm.avatar) {
        return `/img/avatars/${userForm.avatar}`;
    }

    return '/img/avatars/palweb01.webp';
});

const avatarPreviewUser = computed(() => ({
    ...userForm,
    avatar_url: selectedAvatarUrl.value,
}));

onMounted(async () => {
    await loadUserForm().then(async (loadedUser) => {
        uploadedAvatars.value = loadedUser?.uploaded_avatars ?? [];
        await loadTeacherForm();
        showTeacherForm.value = teacherExists.value;
    });
});

watch(teacherExists, (exists) => {
    showTeacherForm.value = exists || showTeacherForm.value;
});

const initializeTeacherForm = () => {
    showTeacherForm.value = true;
};

const removeTeacher = async () => {
    const response = await deleteTeacher();

    if (response.data.success) {
        showTeacherForm.value = false;
        clearTeacherForm();
    }
};

const selectAvatar = (avatar) => {
    userForm.avatar_id = avatar?.id ?? null;

    if (avatar?.filename) {
        userForm.avatar = avatar.filename;
    }
};

const updateUploadedAvatars = (avatars) => {
    uploadedAvatars.value = avatars;
};

const updateUser = (updatedUser) => {
    user.value = updatedUser;
};

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Edit Profile"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingUserForm"/>
        <template v-else-if="userNotFound">
            <AppTip>
                <p>Sorry, the requested User could not be found.</p>
            </AppTip>
            <Link class="portal-button" :href="route('users.index')">
                Back to Community
            </Link>
        </template>

        <div v-else class="window-container">
            <div class="window-header">
                <Link class="material-symbols-rounded" :href="route('users.show', user?.username ?? props.username)">
                    arrow_back
                </Link>
                <button @click="userForm.private = !userForm.private" class="material-symbols-rounded">
                    {{ userForm.private ? 'lock' : 'public' }}
                </button>
                <div class="window-header-url">www.palweb.app/hub/users/{user}</div>
            </div>
            <div class="window-section-head">
                <h1>profile</h1>
                <Link :href="route('users.show', user?.username ?? props.username)" class="material-symbols-rounded">
                    visibility
                </Link>
            </div>
            <div class="user-item l">
                <UserAvatarWrapper :user="avatarPreviewUser">
                    <button type="button" @click="showAvatarPicker = true" class="material-symbols-rounded">
                        photo
                    </button>
                </UserAvatarWrapper>
                <div class="user-data-wrapper">
                    <div class="form-body">
                        <div class="field-item">
                            <label>Name</label>
                            <div class="field-input">
                                <input type="text" v-model="userForm.name" placeholder="Rafiq" required>
                                <div class="field-chars"
                                     :class="{'invalid': userForm.name.length > 50}"
                                     v-text="50 - userForm.name.length"
                                />
                            </div>
                            <div v-if="userErrors.name" v-text="userErrors.name" class="field-error"/>
                        </div>
                        <div class="field-item">
                            <label>Username</label>
                            <div class="field-input">
                                <input type="text" v-model="userForm.username" placeholder="permanent.intifada"
                                       required>
                                <div class="field-chars"
                                     :class="{'invalid': userForm.username.length > 50}"
                                     v-text="50 - userForm.username.length"
                                />
                            </div>
                            <div v-if="userErrors.username" v-text="userErrors.username" class="field-error"/>
                        </div>
                        <div class="field-item">
                            <div style="display: flex; align-items: center; gap: 3.2rem;">
                                <label>Arabic Name</label>
                                <button type="button" @click="userForm.ar_name = generateArabicName()">
                                    Randomize
                                </button>
                            </div>
                            <div class="field-input">
                                <input type="text" v-model="userForm.ar_name" placeholder="رفيق" required>
                                <div class="field-chars"
                                     :class="{'invalid': userForm.ar_name.length > 50}"
                                     v-text="50 - userForm.ar_name.length"
                                />
                            </div>
                            <div v-if="userErrors.ar_name" v-text="userErrors.ar_name" class="field-error"/>
                        </div>
                    </div>
                    <div class="user-comment">
                        <textarea class="user-comment-content" v-model="userForm.bio"
                                  :placeholder="`Sadly, ${userForm.name} hasn't told us anything about themselves yet.`"
                        />
                        <div class="user-comment-data">
                            Joined on {{ user?.created_at }} ({{ user?.created_ago }}).
                        </div>
                        <div v-if="userErrors.bio" v-text="userErrors.bio" class="field-error"/>
                    </div>
                    <div class="user-tag-wrapper">
                        <div class="user-tag">
                            <div class="material-symbols-rounded">location_on</div>
                            <input type="text" v-model="userForm.home"
                                   style="background: none"
                                   placeholder="Earth, probably."
                            />
                        </div>
                        <div v-if="userErrors.home" v-text="userErrors.home" class="field-error"/>
                        <div class="user-tag">
                            <div class="material-symbols-rounded">lips</div>
                            <select v-model="userForm.dialect_id">
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
            <div class="window-footer">
                <button :disabled="isSavingUser || !isUserDirty || !isValidUserRequest" @click="saveUser">
                    save
                </button>
                <button :disabled="isSavingUser || !isUserDirty" @click="resetUser()">
                    reset
                </button>
            </div>

            <div v-if="canManageTeacher" class="window-section-head">
                <h2>teacher</h2>
                <button v-if="!teacherFormExists" @click="initializeTeacherForm" class="material-symbols-rounded">add
                </button>
                <button v-else-if="teacherExists" @click="removeTeacher" :disabled="isDeletingTeacher"
                        class="material-symbols-rounded">delete
                </button>
            </div>
            <AppTip v-if="canCreateTeacher">
                <p v-if="!teacherFormExists">You are eligible to create a Teacher profile. Click the + button to start
                    creating one now.</p>
                <p v-else>Fill out the following information to create your Teacher profile. It will only be created
                    once you Save.</p>
            </AppTip>
            <template v-if="canManageTeacher && teacherFormExists">
                <AppTip v-if="!isStudent">
                    <p>You don't have a Student subscription. You may continue to manage your Teacher profile here, but
                        it will not be visible to others. Please renew your subscription to make your Teacher profile
                        public.</p>
                </AppTip>
                <div class="form-body">
                    <div class="field-item">
                        <label>Email</label>
                        <div class="field-input">
                            <input type="email" v-model="teacherForm.email" placeholder="teacher@example.com" required>
                            <div class="field-chars" :class="{'invalid': teacherForm.email.length > 255}"
                                 v-text="255 - teacherForm.email.length"/>
                        </div>
                        <div v-if="teacherErrors.email" v-text="teacherErrors.email" class="field-error"/>
                    </div>

                    <div class="field-item">
                        <label>Bio</label>
                        <div class="field-input">
                            <textarea v-model="teacherForm.bio" rows="10"
                                      placeholder="What would you like us to know about you as a teacher?"/>
                            <div class="field-chars" :class="{'invalid': teacherForm.bio.length > 5000}"
                                 v-text="5000 - teacherForm.bio.length"/>
                        </div>
                        <div v-if="teacherErrors.bio" v-text="teacherErrors.bio" class="field-error"/>
                    </div>
                </div>
                <div class="window-footer">
                    <button :disabled="isSavingTeacher || !isTeacherDirty || !isValidTeacherRequest"
                            @click="saveTeacher">
                        save
                    </button>
                    <button :disabled="isSavingTeacher || !isTeacherDirty" @click="resetTeacher()">
                        reset
                    </button>
                </div>
            </template>
        </div>
    </div>

    <AvatarPicker v-model="showAvatarPicker"
                  :user="user"
                  :default-avatar="userForm.avatar"
                  :selected-avatar-id="userForm.avatar_id"
                  :uploaded-avatars="uploadedAvatars"
                  @select="selectAvatar"
                  @uploaded-avatars-updated="updateUploadedAvatars"
                  @user-updated="updateUser"/>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
