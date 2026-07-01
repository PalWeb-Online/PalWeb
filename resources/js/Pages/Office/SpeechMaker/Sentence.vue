<script setup>
import {computed, onMounted, watch} from "vue";
import draggable from 'vuedraggable';
import {useSearchStore} from "../../../stores/SearchStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import AppTip from "../../../components/AppTip.vue";
import {route} from "ziggy-js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import PinButton from "../../../components/PinButton.vue";
import SentenceActions from "../../../components/Actions/SentenceActions.vue";
import {useSentenceEditor} from "../../../composables/sentences/useSentenceEditor.js";
import {useSentenceValidation} from "../../../composables/sentences/useSentenceValidation.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    initialDialog: {
        type: Object,
        required: false,
    },
    sentenceId: {
        type: Number,
        default: null,
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
    sentence,
    sentenceNotFound,
    loadForm,
    reloadForm,
    saveSentence,
    addTerm,
    insertTerm,
    removeTerm,
    updatePosition,
} = useSentenceEditor({
    sentenceId: computed(() => props.sentenceId),
    initialDialog: computed(() => props.initialDialog),
});

const {
    isValidRequest,
    validationErrors,
} = useSentenceValidation({
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
                insertTerm(newModel);
                SearchStore.deselectModel();
            }
        }
    );
});

watch(() => props.sentenceId, async () => {
    await reloadForm();
});
</script>

<template>
    <Head title="Speech Maker: Build Sentence"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="sentenceNotFound">
            <AppTip>
                <p>Sorry, the requested Sentence could not be found.</p>
            </AppTip>
            <Link class="portal-button" :href="route('speech-maker.index')">
                Back to Speech Maker
            </Link>
        </template>

        <div v-else class="window-container">
            <div class="window-header">
                <Link :href="initialDialog
                        ? route('speech-maker.dialog', initialDialog.id)
                        : route('speech-maker.index')"
                      class="material-symbols-rounded">arrow_back
                </Link>
                <div class="window-header-url">www.palweb.app/library/sentences/{sentence}</div>
                <button class="material-symbols-rounded" @click="addTerm">
                    add
                </button>
                <button class="material-symbols-rounded" @click="SearchStore.openSearchGenie('insert', 'terms')">
                    place_item
                </button>
                <button :disabled="isSaving || !hasNavigationGuard || !isValidRequest"
                        class="material-symbols-rounded" @click="saveSentence">
                    save
                </button>
                <button :disabled="isSaving || !hasNavigationGuard"
                        class="material-symbols-rounded" @click="reset()">
                    undo
                </button>
            </div>
            <AppTip>
                <p>The Sentence is currently {{ sentence?.id ? 'Published' : 'a Draft' }}.</p>
                <template v-if="!isValidRequest">
                    <p style="font-weight: 700">The Sentence cannot be saved in the current state.</p>
                    <ul>
                        <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                    </ul>
                </template>
            </AppTip>
            <div class="window-section-head">
                <h1>sentence</h1>
                <PinButton v-if="sentence?.id" modelType="sentence" :model="sentence"/>
                <SentenceActions v-if="sentence?.id" :model="sentence"/>
            </div>
            <AppTip dismissable v-if="initialDialog">
                <p>Creating a Sentence within the Dialog (<b>{{ initialDialog.title }}</b>). It will be created
                    & added to the Dialog on Save.
                </p>
            </AppTip>
            <div class="model-item-container sentence-item-container l">
                <div v-if="form.dialog?.id" class="sentence-dialog-data">
                    <Link :href="route('speech-maker.dialog', form.dialog.id)" target="_blank">
                        <div>dialog</div>
                        <div>{{ form.dialog.title }}</div>
                    </Link>
                    <div>
                        <div>speaker</div>
                        <input v-model="form.speaker"/>
                    </div>
                </div>
                <div class="model-item sentence-item">
                    <div class="model-item-content">
                        <div v-if="form.terms.length > 0" class="sentence-term" v-for="term in form.terms">
                            <template v-if="!term.sentencePivot.toggleable">
                                <div>{{ term.sentencePivot.sent_term }}</div>
                                <div>{{ term.sentencePivot.sent_translit }}</div>
                            </template>
                            <template v-else>
                                <div>ــــــــ</div>
                                <div>[]</div>
                            </template>
                        </div>
                    </div>
                </div>
                <input class="model-item-description" v-model="form.trans"/>
            </div>

            <div class="window-section-head">
                <h2>terms</h2>
            </div>
            <AppTip v-if="form.dialog?.id">
                <p>Since this Sentence appears in a Dialog, you may select the Terms that should be toggled off if the
                    Student wants to use the Dialog for conversation practice. (The Terms will be visible under all
                    other
                    circumstances.)</p>
            </AppTip>
            <draggable :list="form.terms" itemKey="sentencePivot.uuid" handle=".handle" @end="updatePosition()"
                       class="model-list index-list draggable">
                <template #item="{ element, index }">
                    <div class="draggable-item">
                    <span class="delete material-symbols-rounded"
                          @click="removeTerm(index)">delete</span>
                        <span v-if="form.dialog?.id" class="material-symbols-rounded"
                              :class="{toggleable: element.sentencePivot.toggleable}"
                              @click="element.sentencePivot.toggleable = !element.sentencePivot.toggleable">
                        {{ element.sentencePivot.toggleable ? 'visibility_off' : 'visibility' }}
                    </span>
                        <div class="model-item-container term-item-container">
                            <div class="model-item term-item">
                                <div class="model-item-content">
                                    <div class="term-item-gloss">
                                        <template v-if="element.glosses">
                                            <select v-model="element.sentencePivot.gloss_id">
                                                <option v-for="gloss in element.glosses" :value="gloss.id">
                                                    {{
                                                        gloss.gloss.length > 85 ? gloss.gloss.slice(0, 85) + "..." : gloss.gloss
                                                    }}
                                                </option>
                                            </select>
                                        </template>
                                    </div>
                                    <div class="term-item-term">
                                        <input class="arb" v-model="element.sentencePivot.sent_term"/>
                                        <input class="translit" v-model="element.sentencePivot.sent_translit"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="handle material-symbols-rounded">drag_indicator</span>
                    </div>
                </template>
            </draggable>
            <div class="terms-count">{{ form.terms.filter(term => term.id).length }} Terms</div>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            v-if="showAlert"
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
