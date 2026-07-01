<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";
import {route} from "ziggy-js";
import {CircleStencil, Cropper} from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";
import ModalWrapper from "./Modals/ModalWrapper.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";
import WindowSection from "./WindowSection.vue";
import AppTip from "./AppTip.vue";
import {useUser} from "../composables/users/useUser.js";

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    defaultAvatar: {
        type: String,
        default: '',
    },
    selectedAvatarId: {
        type: Number,
        default: null,
    },
    uploadedAvatars: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits([
    'update:modelValue',
    'select',
    'uploaded-avatars-updated',
    'user-updated',
]);

const {isStudent} = useUser(computed(() => props.user));

const NotificationStore = useNotificationStore();
const maxAvatarFileSize = 5 * 1024 * 1024;
const allowedAvatarMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
const allowedAvatarExtensions = ['jpg', 'jpeg', 'png', 'webp'];

const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

const defaultAvatars = ref([]);
const localUploadedAvatars = ref([...props.uploadedAvatars]);
const uploadInput = ref(null);
const cropper = ref(null);
const uploadFile = ref(null);
const uploadPreviewUrl = ref(null);
const uploadError = ref('');
const isUploadingAvatar = ref(false);
const deletingAvatarIds = ref(new Set());

const maxUploadedAvatars = 8;
const hasReachedAvatarLimit = computed(() => localUploadedAvatars.value.length >= maxUploadedAvatars);
const canCreateCustomAvatar = computed(() => canSelectCustomAvatar.value && !hasReachedAvatarLimit.value);
const canSelectCustomAvatar = computed(() => isStudent.value);

watch(
    () => props.uploadedAvatars,
    (avatars) => {
        localUploadedAvatars.value = [...avatars];
    },
    {deep: true}
);

onMounted(async () => {
    const response = await axios.get(route('avatars.get'));
    defaultAvatars.value = response.data;
});

const selectAvatar = (avatar) => {
    emit('select', avatar);
    isOpen.value = false;
};

const clearUploadPreview = () => {
    uploadFile.value = null;
    uploadError.value = '';

    if (uploadPreviewUrl.value) {
        URL.revokeObjectURL(uploadPreviewUrl.value);
        uploadPreviewUrl.value = null;
    }

    if (uploadInput.value) {
        uploadInput.value.value = '';
    }
};

const getFileExtension = (file) => {
    return file.name.split('.').pop()?.toLowerCase() ?? '';
};

const validateStagedFile = (file) => {
    if (file.size > maxAvatarFileSize) {
        return 'Avatar images must be 5MB or smaller.';
    }

    if (!allowedAvatarMimeTypes.includes(file.type) || !allowedAvatarExtensions.includes(getFileExtension(file))) {
        return 'Avatar images must be JPG, JPEG, PNG, or WEBP files.';
    }

    return null;
};

const handleUploadSelection = (event) => {
    const file = event.target.files?.[0] ?? null;

    clearUploadPreview();

    if (!file) return;

    if (hasReachedAvatarLimit.value) {
        uploadError.value = `You may upload up to ${maxUploadedAvatars} custom avatars.`;
        NotificationStore.addNotification(uploadError.value, 'error');
        return;
    }

    const validationError = validateStagedFile(file);

    if (validationError) {
        uploadError.value = validationError;
        NotificationStore.addNotification(validationError, 'error');
        return;
    }

    uploadFile.value = file;
    uploadPreviewUrl.value = URL.createObjectURL(file);
};

const syncUploadedAvatars = (avatars) => {
    localUploadedAvatars.value = avatars;
    emit('uploaded-avatars-updated', avatars);
};

const getCroppedAvatarBlob = async () => {
    const result = cropper.value?.getResult();

    if (!result?.canvas) {
        throw new Error('The Avatar could not be cropped.');
    }

    return await new Promise((resolve, reject) => {
        result.canvas.toBlob((blob) => {
            blob ? resolve(blob) : reject(new Error('The Avatar could not be cropped.'));
        }, uploadFile.value.type, 0.92);
    });
};

const uploadAvatar = async () => {
    if (!uploadFile.value || isUploadingAvatar.value || hasReachedAvatarLimit.value) return;

    isUploadingAvatar.value = true;
    uploadError.value = '';

    try {
        const croppedAvatar = await getCroppedAvatarBlob();
        const payload = new FormData();
        payload.append('avatar', croppedAvatar, uploadFile.value.name);

        const response = await axios.post(route('users.avatars.store', props.user.username), payload);
        const avatar = response.data.avatar;

        syncUploadedAvatars([
            avatar,
            ...localUploadedAvatars.value.filter((uploadedAvatar) => uploadedAvatar.id !== avatar.id),
        ]);

        clearUploadPreview();
        NotificationStore.addNotification('OK, your Avatar was successfully uploaded.', 'success');

    } catch (error) {
        uploadError.value = error?.response?.data?.errors?.avatar?.[0]
            ?? error?.response?.data?.message
            ?? 'The Avatar could not be uploaded.';

        NotificationStore.addNotification(uploadError.value, 'error');

    } finally {
        isUploadingAvatar.value = false;
    }
};

const zoomCropper = (factor) => {
    cropper.value?.zoom(factor);
};

const deleteAvatar = async (avatar) => {
    if (!confirm('Delete this uploaded avatar?')) return;

    deletingAvatarIds.value = new Set([...deletingAvatarIds.value, avatar.id]);

    try {
        const response = await axios.delete(route('avatars.destroy', avatar.id));
        const savedUser = response.data.user;
        const avatars = savedUser?.uploaded_avatars
            ?? localUploadedAvatars.value.filter((uploadedAvatar) => uploadedAvatar.id !== avatar.id);

        syncUploadedAvatars(avatars);

        if (props.selectedAvatarId === avatar.id) {
            emit('select', null);
        }

        if (savedUser) {
            emit('user-updated', savedUser);
        }

        NotificationStore.addNotification('OK, your Avatar was successfully deleted.', 'success');

    } catch (error) {
        NotificationStore.addNotification(
            error?.response?.data?.message ?? 'The Avatar could not be deleted.',
            'error',
        );

    } finally {
        const nextDeletingAvatarIds = new Set(deletingAvatarIds.value);
        nextDeletingAvatarIds.delete(avatar.id);
        deletingAvatarIds.value = nextDeletingAvatarIds;
    }
};

onBeforeUnmount(() => {
    clearUploadPreview();
});
</script>

<template>
    <ModalWrapper v-model="isOpen">
        <div class="window-container modal-container">
            <div class="window-section-head">
                <h1>avatar</h1>
            </div>
            <div class="modal-container-body">
                <template v-if="canSelectCustomAvatar || localUploadedAvatars.length">
                    <div class="window-section-head">
                        <h2>custom</h2>
                    </div>
                    <template v-if="localUploadedAvatars.length">
                        <div class="window-section-head">
                            <h3>my uploads</h3>
                        </div>
                        <AppTip v-if="!canSelectCustomAvatar">
                            <p>You cannot use a custom avatar while your Student subscription is inactive. You may
                                delete
                                your uploaded images if you wish, or keep them to use again if your restore your
                                subscription.</p>
                        </AppTip>
                        <section class="avatar-picker-section">
                            <div class="avatar-grid">
                                <div class="avatar-tile"
                                     v-for="avatar in localUploadedAvatars" :key="avatar.id"
                                     :class="{selected: selectedAvatarId === avatar.id}"
                                >
                                    <div class="avatar-selected material-symbols-rounded">check</div>
                                    <button type="button"
                                            class="avatar-choice"
                                            :disabled="!canSelectCustomAvatar"
                                            @click="selectAvatar(avatar)">
                                        <img :src="avatar.url" alt="Avatar"/>
                                    </button>
                                    <button type="button"
                                            class="avatar-delete material-symbols-rounded"
                                            :disabled="deletingAvatarIds.has(avatar.id)"
                                            @click.stop="deleteAvatar(avatar)">
                                        delete
                                    </button>
                                </div>
                            </div>
                        </section>
                    </template>
                    <template v-if="canSelectCustomAvatar">
                        <div class="window-section-head">
                            <h3>upload new</h3>
                        </div>
                        <AppTip>
                            <p v-if="hasReachedAvatarLimit">
                                You may upload up to 8 custom avatars. Delete one before uploading another.
                            </p>
                            <p v-else>
                                You may upload an image in the JPG, JPEG, PNG, or WEBP formats (max. 5MB).
                            </p>
                        </AppTip>
                        <section v-if="canCreateCustomAvatar" class="avatar-picker-section avatar-upload-section">
                            <input ref="uploadInput"
                                   type="file"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="avatar-file-input"
                                   @change="handleUploadSelection">
                            <div v-if="uploadPreviewUrl" class="avatar-cropper-panel">
                                <Cropper ref="cropper"
                                         class="avatar-cropper"
                                         :src="uploadPreviewUrl"
                                         :stencil-component="CircleStencil"
                                         :stencil-props="{aspectRatio: 1}"
                                         :auto-zoom="true"
                                         image-restriction="stencil"/>
                                <div class="avatar-upload-actions">
                                    <button type="button" class="material-symbols-rounded" @click="zoomCropper(0.9)">
                                        zoom_out
                                    </button>
                                    <button type="button" class="material-symbols-rounded" @click="zoomCropper(1.1)">
                                        zoom_in
                                    </button>
                                    <button type="button"
                                            class="material-symbols-rounded"
                                            @click="clearUploadPreview">
                                        close
                                    </button>
                                    <button type="button"
                                            class="material-symbols-rounded"
                                            :disabled="isUploadingAvatar"
                                            @click="uploadAvatar">
                                        check
                                    </button>
                                </div>
                            </div>
                            <button v-else type="button" class="material-symbols-rounded" @click="uploadInput?.click()">
                                upload
                            </button>

                            <div v-if="uploadError" v-text="uploadError" class="field-error"/>
                        </section>
                    </template>
                </template>

                <WindowSection>
                    <template #title>
                        <h2>defaults</h2>
                    </template>
                    <template #content>
                        <section class="avatar-picker-section">
                            <div class="avatar-grid">
                                <div class="avatar-tile"
                                     v-for="avatar in defaultAvatars" :key="avatar.filename"
                                     :class="{selected: !selectedAvatarId && defaultAvatar === avatar.filename}"
                                >
                                    <div class="avatar-selected material-symbols-rounded">check</div>
                                    <button type="button" class="avatar-choice" @click="selectAvatar(avatar)">
                                        <img :src="avatar.url" alt="Avatar"/>
                                    </button>
                                </div>
                            </div>
                        </section>
                    </template>
                </WindowSection>
            </div>
        </div>
    </ModalWrapper>
</template>

<style scoped lang="scss">
.avatar-picker-section {
    font-size: 1.6rem;
    padding: 2.4rem;
}

.avatar-upload-section {
    .material-symbols-rounded {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 3.6rem;
        height: 3.6rem;
        font-size: 2.0rem;
        border-radius: 50%;
        background: var(--color-medium-primary);
        color: white;
    }
}

.avatar-grid {
    display: grid;
    gap: 1.6rem;
    grid-template-columns: repeat(auto-fill, minmax(12.8rem, 1fr));

    .avatar-choice,
    .avatar-tile {
        aspect-ratio: 1;
        min-width: 0;
    }

    .avatar-choice {
        background: none;
        border: 0;
        padding: 0;
        width: 100%;
        cursor: pointer;

        &[disabled] {
            filter: grayscale(1) opacity(0.5);
            cursor: not-allowed;
        }
    }

    img {
        object-fit: cover;
        height: 100%;
        width: 100%;
        padding: 0.4rem;
        border-radius: 1.6rem;
    }
}

.avatar-tile {
    position: relative;
    border-radius: 1.6rem;
    border: 0.2rem solid var(--color-pastel-dark);
    overflow: hidden;

    &:hover, &.selected {
        border-color: var(--color-dark-primary);
    }

    &.selected .avatar-selected,
    &:hover .avatar-delete {
        display: flex;
    }

    .avatar-selected, .avatar-delete {
        position: absolute;
        inset-block-start: 0;
        display: none;
        align-items: center;
        justify-content: center;
        min-width: 0;
        width: 3.6rem;
        height: 3.6rem;
        font-size: 2.0rem;
        border: 0.2rem solid var(--color-dark-primary);
        z-index: 1;
    }

    .avatar-selected {
        color: white;
        background: var(--color-medium-secondary);
        inset-inline-start: 0;
        padding: 0 0.2rem 0.2rem 0;
        border-radius: 0 0 50%;
        border-width: 0 0.2rem 0.2rem 0;
    }

    .avatar-delete {
        inset-inline-end: 0;
        padding: 0 0 0.2rem 0.2rem;
        border-radius: 0 0 0 50%;
        border-width: 0 0 0.2rem 0.2rem;
        color: var(--color-dark-primary);
        background: white;
    }
}

.avatar-file-input {
    display: none;
}

.avatar-cropper-panel {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
    width: min(100%, 42rem);
}

.avatar-cropper {
    width: 100%;
    aspect-ratio: 1;
    max-height: 42rem;
}

.avatar-upload-actions {
    display: flex;
    gap: 0.8rem;
}
</style>
