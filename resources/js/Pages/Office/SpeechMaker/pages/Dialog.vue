<script setup>
import {computed, onMounted, ref, watch} from "vue";
import draggable from 'vuedraggable';
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import DialogActions from "../../../../components/Actions/DialogActions.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";
import SentenceActions from "../../../../components/Actions/SentenceActions.vue";

const props = defineProps({
    dialog: Object,
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const dialog = useForm({
    id: props.dialog?.id || null,
    title: props.dialog?.title || '',
    description: props.dialog?.description || '',
    media: props.dialog?.media || '',
    sentences: props.dialog?.sentences || [],
    published: props.dialog?.published || false,
});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return dialog.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertSentence = async (sentence) => {
    const sentenceExists = dialog.sentences.some(existingSentence => existingSentence.id === sentence.id);

    if (sentenceExists) {
        NotificationStore.addNotification('This Sentence is already in the Dialog!', 'error');

    } else if (sentence.dialog) {
        NotificationStore.addNotification('This Sentence is already in a Dialog!', 'error');

    } else {
        const response = await axios.get(route('speech-maker.get-terms', sentence.id));

        dialog.sentences.push({
            id: sentence.id,
            sentence: sentence.sentence,
            translit: sentence.translit,
            trans: sentence.trans,
            terms: response.data.terms,
        });

        updatePosition();
        NotificationStore.addNotification(`Added ${sentence.sentence} to the Dialog!`);
    }
}

const removeSentence = (index) => {
    dialog.sentences.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    dialog.sentences.forEach((sentence, index) => {
        sentence.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    if (!dialog.title) return false;

    for (const sentence of dialog.sentences) {
        if (!sentence.speaker || !sentence.position) {
            return false;
        }
    }

    return true;
});

const saveDialog = async () => {
    isSaving.value = true;

    const method = dialog.id
        ? dialog.patch.bind(dialog)
        : dialog.post.bind(dialog);

    const url = dialog.id
        ? route('dialogs.update', dialog.id)
        : route('dialogs.store');

    method(url, {
        onSuccess: () => {
            dialog.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Dialog could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

watch(
    () => props.dialog,
    (newValue) => {
        if (newValue) {
            Object.assign(dialog, newValue);
        }
    },
    {deep: true}
);

onMounted(() => {
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
</script>

<template>
    <div class="window-container">
        <div class="window-header">
            <Link :href="route('speech-maker.index')" class="material-symbols-rounded">arrow_back</Link>
            <button @click="dialog.published = !dialog.published" class="material-symbols-rounded">
                {{ !dialog.published ? 'lock' : 'public' }}
            </button>
            <div class="window-header-url">www.palweb.app/academy/dialogs/{dialog}</div>
            <button class="material-symbols-rounded"
                    @click="router.get(route('speech-maker.dialog-sentence', dialog.id))" :disabled="!dialog.id">
                add
            </button>
            <button class="material-symbols-rounded" @click="SearchStore.openSearchGenie('insert', 'sentences')">
                place_item
            </button>
            <button class="material-symbols-rounded" @click="saveDialog"
                    :disabled="dialog.processing || !hasNavigationGuard || !isValidRequest">
                save
            </button>
            <button class="material-symbols-rounded" @click="dialog.reset()"
                    :disabled="dialog.processing || !hasNavigationGuard">
                undo
            </button>
        </div>
        <div class="window-section-head">
            <h1>dialog</h1>
            <DialogActions v-if="dialog.id" :model="props.dialog"/>
        </div>

        <div class="window-content-head">
            <input class="window-content-head-title" style="direction: rtl" v-model="dialog.title"
                   placeholder="Required: Dialog Title"
            />
            <textarea class="dialog-description" v-model="dialog.description"/>
        </div>

        <div class="window-section-head">
            <h2>media</h2>
        </div>
        <input v-model="dialog.media" style="margin: 1.6rem; width: 50%"/>

        <div class="window-section-head">
            <h2>transcript</h2>
        </div>
        <draggable class="dialog-body draggable" :list="dialog.sentences" itemKey="id" handle=".handle"
                   @end="updatePosition()">
            <template #item="{ element, index }">
                <div class="draggable-item">
                    <span class="delete material-symbols-rounded"
                          v-show="dialog.sentences.length > 0"
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

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
