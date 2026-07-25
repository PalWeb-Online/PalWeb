<script setup>
import {computed, onMounted, watch} from "vue";
import {route} from "ziggy-js";
import draggable from 'vuedraggable';
import TermItem from "./UI/TermItem.vue";
import {useSearchStore} from "../../../stores/SearchStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import PinButton from "../../../components/PinButton.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import UserItem from "../../../components/UserItem.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import Layout from "../../../Shared/Layout.vue";
import {useDeckEditor} from "../../../composables/decks/useDeckEditor.js";
import {useDeckValidation} from "../../../composables/decks/useDeckValidation.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";
import {Link} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

const props = defineProps({
    deckId: {
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
    deck,
    deckNotFound,
    loadForm,
    reloadForm,
    saveDeck,
    insertTerm,
    removeTerm,
    updatePosition,
} = useDeckEditor({
    deckId: computed(() => props.deckId),
});

const {
    validationErrors,
    isValidRequest,
} = useDeckValidation({
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

watch(() => props.deckId, async () => {
    await reloadForm();
});
</script>

<template>
    <Head title="Deck Master: Build Deck"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="deckNotFound">
            <AppTip>
                <p>{{ $t('pages.common.not-found', {model: $t('actions.models.deck')}) }}</p>
            </AppTip>
            <Link class="portal-button" :href="route('deck-master.index')">Back to Deck Master</Link>
        </template>

        <div v-else class="window-container">
            <div class="window-header">
                <Link :href="route('deck-master.index', {mode: 'build'})" class="material-symbols-rounded">
                    arrow_back
                </Link>
                <button @click="form.private = !form.private" class="material-symbols-rounded">
                    {{ form.private ? 'lock' : 'public' }}
                </button>
                <div class="window-header-url">www.palweb.app/library/decks/{deck}</div>
                <button @click="SearchStore.openSearchGenie('insert', 'terms')"
                        class="material-symbols-rounded">
                    place_item
                </button>
                <button :disabled="isSaving || !hasNavigationGuard || !isValidRequest" @click="saveDeck"
                        class="material-symbols-rounded">
                    save
                </button>
                <button :disabled="isSaving || !hasNavigationGuard" @click="reset()"
                        class="material-symbols-rounded">
                    undo
                </button>
            </div>
            <AppTip>
                <p>{{
                        $t('forms.messages.current-status', {
                            model: $t('actions.models.deck'),
                            status: deck?.private ? $t('forms.status.private') : $t('forms.status.public')
                        })
                    }}</p>
                <template v-if="Object.keys(validationErrors).length">
                    <p style="font-weight: 700">{{ $t('forms.messages.has-validation-errors', {model: $t('actions.models.deck')}) }}</p>
                    <ul>
                        <li v-for="(issue, i) in validationErrors" :key="i">{{ issue }}</li>
                    </ul>
                </template>
            </AppTip>
            <div class="window-section-head">
                <h1>{{ $t('components.deck.title') }}</h1>
                <PinButton v-if="deck?.id" modelType="deck" :model="deck"/>
                <DeckActions v-if="deck?.id" :model="deck"/>
            </div>
            <div class="window-content-head">
                <input class="window-content-head-title" v-model="form.name"
                       :placeholder="$t('forms.fields.title')"
                />
            </div>
            <UserItem :user="form.author" size="m" comment>
                <template #comment>
                        <textarea class="user-comment-content" v-model="form.description"
                                  :placeholder="$t('components.deck.description-placeholder', {author: form.author.name})"
                        />
                    <div v-if="form.id" class="user-comment-data">
                        {{ $t('components.deck.created-by', {author: form.author.name, date: form.created_at}) }}
                    </div>
                </template>
            </UserItem>

            <div class="window-section-head">
                <h2>{{ $t('models.terms') }}</h2>
            </div>
            <draggable :list="form.terms" itemKey="id" handle=".handle"
                       @end="updatePosition()"
                       class="model-list index-list draggable">
                <template #item="{ element, index }">
                    <div class="draggable-item">
                        <span class="delete material-symbols-rounded"
                              v-show="form.terms.length > 0"
                              @click="removeTerm(index)">delete</span>
                        <TermItem :term="element"/>
                        <span class="handle material-symbols-rounded">drag_indicator</span>
                    </div>
                </template>
            </draggable>

            <div class="terms-count">{{ $t('components.common.counts.terms', {count: form.terms.length}) }}</div>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="$t('modals.nav-guard.messages.unsaved-changes')"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>

<style scoped lang="scss">
.deck-editor {
    display: grid;
    gap: 2.4rem;
    width: 100%;
    max-width: 96rem;
    justify-items: center;
}
</style>
