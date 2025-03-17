<script setup>
import {computed, onMounted, reactive, ref, watch} from "vue";
import {route} from "ziggy-js";
import {cloneDeep, isEqual} from "lodash";
import draggable from 'vuedraggable';
import TermItem from "../ui/TermItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/DeckActions.vue";
import AppAlert from "../../../../components/AppAlert.vue";
import {Inertia} from "@inertiajs/inertia";
import {useUserStore} from "../../../../stores/UserStore.js";

const props = defineProps({
    deck: Object,
});

const UserStore = useUserStore();
const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const stagedDeck = reactive({
    id: null,
    name: '',
    description: '',
    private: false,
    isPinned: false,
    pinCount: 0,
    created_at: null,
    author: {
        id: UserStore.user.id,
        name: UserStore.user.name,
        username: UserStore.user.username,
        avatar: UserStore.user.avatar,
    },
    terms: [],
});

const originalDeck = ref(null);

const resetDeck = async () => {
    Object.assign(stagedDeck, cloneDeep(originalDeck.value));
};

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return !isEqual(stagedDeck, originalDeck.value) && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertTerm = (term) => {
    const termExists = stagedDeck.terms.some(existingTerm => existingTerm.id === term.id);

    if (termExists) {
        NotificationStore.addNotification('This Term is already in the Deck!', 'error');

    } else {
        stagedDeck.terms.push({
            id: term.id,
            term: term.term,
            category: term.category,
            translit: term.translit,
            glosses: term.glosses.map((gloss) => ({
                id: gloss.id,
                gloss: gloss.gloss,
            })),
            deckPivot: {
                gloss_id: term.glosses[0].id,
                position: '',
            }
        });
        updatePosition();
        NotificationStore.addNotification(`Added ${term.term} to the Deck!`);
    }
}

const removeTerm = (index) => {
    stagedDeck.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    stagedDeck.terms.forEach((term, index) => {
        term.deckPivot.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    return stagedDeck.name;
});

const saveDeck = async () => {
    isSaving.value = true;

    const method = stagedDeck.id
        ? Inertia.patch.bind(Inertia)
        : Inertia.post.bind(Inertia);

    const url = stagedDeck.id
        ? route('decks.update', stagedDeck.id)
        : route('decks.store');

    method(url, {deck: stagedDeck},
        {
            onSuccess: () => {
                NotificationStore.addNotification('The Deck has been saved!');
                originalDeck.value = cloneDeep(stagedDeck);
                isSaving.value = false;
            },
            onError: () => {
                NotificationStore.addNotification('Oh no! The Deck could not be saved.');
                isSaving.value = false;
            }
        }
    );
};

watch(
    () => props.deck,
    (newValue) => {
        if (newValue) {
            Object.assign(stagedDeck, newValue);
        }
    },
    {deep: true}
);

onMounted(async () => {
    if (!!props.deck) {
        Object.assign(stagedDeck, props.deck);
    }

    originalDeck.value = cloneDeep(stagedDeck);

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
</script>

<template>
    <div class="app-nav-interact">
        <img src="/img/finger-back.svg" @click="Inertia.get(route('deck-master.index', {mode: 'build'}))" alt="Back"/>
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="isSaving || !hasNavigationGuard || !isValidRequest" label="Save"
                       @click="saveDeck"
            />
            <AppButton :disabled="isSaving || !hasNavigationGuard" label="Reset"
                       @click="resetDeck"
            />
            <AppButton @click="SearchStore.openSearchGenie('insert', 'terms')"
                       label="Insert Term"
            />
        </div>
    </div>

    <div class="deck-container">
        <div class="deck-container-head">
            <PinButton v-if="stagedDeck.id" modelType="deck"
                       :model="stagedDeck"/>
            <input class="deck-container-head-title" v-model="stagedDeck.name"
                   placeholder="Required: Deck Name"
            />
            <img :class="['lock', { public: !stagedDeck.private }]"
                 :src="`/img/${stagedDeck.private ? 'lock.svg' : 'lock-open.svg'}`"
                 @click="stagedDeck.private = !stagedDeck.private"
                 alt="lock"/>
            <DeckActions v-if="stagedDeck.id" :model="stagedDeck"/>
        </div>
        <div class="user-wrapper">
            <div class="user-avatar">
                <img :src="`/img/avatars/${stagedDeck.author.avatar}`" alt="Profile Picture"/>
            </div>
            <div class="user-comment">
                <div class="user-comment-head">
                    <div>{{ stagedDeck.author.name }}</div>
                    <div>({{ stagedDeck.author.username }})</div>
                </div>
                <div class="user-comment-body">
                    <textarea class="user-comment-body-content"
                              v-model="stagedDeck.description"
                              placeholder="(Optional) Tell us something about this Deck."
                    />
                </div>
            </div>
        </div>

        <draggable :list="stagedDeck.terms" itemKey="id"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="draggable-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete"
                         v-show="stagedDeck.terms.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>

        <div class="deck-term-count">{{ stagedDeck.terms.length }} Terms</div>
    </div>

    <AppAlert
        v-if="showAlert"
        message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
        @confirm="handleConfirm"
        @cancel="handleCancel"
    />
</template>
