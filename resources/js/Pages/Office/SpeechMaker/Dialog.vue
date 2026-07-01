<script setup>
import {computed, onMounted, watch} from "vue";
import draggable from 'vuedraggable';
import {useSearchStore} from "../../../stores/SearchStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import DialogActions from "../../../components/Actions/DialogActions.vue";
import {route} from "ziggy-js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import SentenceActions from "../../../components/Actions/SentenceActions.vue";
import Layout from "../../../Shared/Layout.vue";
import {useDialogEditor} from "../../../composables/dialogs/useDialogEditor.js";
import {useDialogValidation} from "../../../composables/dialogs/useDialogValidation.js";
import AppTip from "../../../components/AppTip.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    dialogId: {
        type: Number,
        default: null
    },
});

const SearchStore = useSearchStore();

const {
    form,
    errors: backendErrors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    dialog,
    dialogNotFound,
    loadForm,
    reloadForm,
    saveDialog,
    insertSentence,
    removeSentence,
    updatePosition,
} = useDialogEditor({
    dialogId: computed(() => props.dialogId),
});

const {
    isValidRequest,
    validationErrors,
} = useDialogValidation({
    form,
    backendErrors,
});

const hasNavigationGuard = computed(() => isDirty.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

onMounted(async () => {
    await loadForm();

    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                insertSentence(newModel);
                SearchStore.deselectModel();
            }
        }
    );
});

watch(() => props.dialogId, async () => {
    await reloadForm();
});
</script>

<template>
    <Head title="Speech Maker: Build Dialog"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="dialogNotFound">
            <AppTip>
                <p>Sorry, the requested Dialog could not be found.</p>
            </AppTip>
            <Link class="portal-button" :href="route('speech-maker.index')">
                Back to Speech Maker
            </Link>
        </template>

        <div v-else class="window-container">
            <div class="window-header">
                <Link :href="route('speech-maker.index')" class="material-symbols-rounded">arrow_back</Link>
                <button @click="form.published = !form.published" class="material-symbols-rounded">
                    {{ !form.published ? 'lock' : 'public' }}
                </button>
                <div class="window-header-url">www.palweb.app/academy/dialogs/{dialog}</div>
                <Link v-if="dialog?.id" class="material-symbols-rounded"
                        :href="route('speech-maker.dialog-sentence', dialog.id)">
                    add
                </Link>
                <button class="material-symbols-rounded" @click="SearchStore.openSearchGenie('insert', 'sentences')">
                    place_item
                </button>
                <button class="material-symbols-rounded" @click="saveDialog"
                        :disabled="isSaving || !hasNavigationGuard || !isValidRequest">
                    save
                </button>
                <button class="material-symbols-rounded" @click="reset()"
                        :disabled="isSaving || !hasNavigationGuard">
                    undo
                </button>
            </div>
            <AppTip>
                <p>The Dialog is currently {{ dialog?.published ? 'Published' : 'a Draft' }}.</p>
                <template v-if="!isValidRequest">
                    <p style="font-weight: 700">The Dialog cannot be saved in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                    </ul>
                </template>
            </AppTip>
            <div class="window-section-head">
                <h1>dialog</h1>
                <DialogActions v-if="dialog?.id" :model="dialog"/>
            </div>
            <div class="window-content-head">
                <input class="window-content-head-title" style="direction: rtl" v-model="form.title"
                       placeholder="Required: Dialog Title"
                />
                <textarea class="dialog-description" v-model="form.description"/>
            </div>
            <div class="window-section-head">
                <h2>media</h2>
            </div>
            <iframe :src="form.media" allowfullscreen></iframe>
            <input v-model="form.media" style="margin: 1.6rem; width: 50%"/>

            <div class="window-section-head">
                <h2>transcript</h2>
            </div>
            <draggable class="dialog-body draggable" :list="form.sentences" itemKey="id" handle=".handle"
                       @end="updatePosition()">
                <template #item="{ element, index }">
                    <div class="draggable-item">
                    <span class="delete material-symbols-rounded"
                          v-show="form.sentences.length > 0"
                          @click="removeSentence(index)">delete</span>
                        <div class="model-item-container sentence-item-container">
                            <div class="sentence-dialog-data">
                                <div>
                                    <div>speaker</div>
                                    <input v-model="element.speaker" placeholder="ناطق"/>
                                </div>
                            </div>
                            <div class="model-item sentence-item">
                                <div class="model-item-content">
                                    <div class="sentence-term" style="background: none">
                                        <div>{{ element.sentence }}</div>
                                    </div>
                                </div>
                                <SentenceActions v-if="element.id" :model="element"/>
                            </div>
                            <div class="model-item-description">
                                {{ element.trans }}
                            </div>
                        </div>
                        <span class="handle material-symbols-rounded">drag_indicator</span>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
