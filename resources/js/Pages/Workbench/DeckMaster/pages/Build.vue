<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {route} from "ziggy-js";
import draggable from 'vuedraggable';
import TermItem from "../ui/TermItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/DeckActions.vue";
import {useUserStore} from "../../../../stores/UserStore.js";
import {router, useForm} from "@inertiajs/vue3";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import UserItem from "../../../../components/UserItem.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";

const props = defineProps({
    deck: Object,
});

const UserStore = useUserStore();
const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const deck = useForm({
    id: props.deck?.id || null,
    name: props.deck?.name || '',
    description: props.deck?.description || '',
    private: props.deck?.private || false,
    isPinned: props.deck?.isPinned || false,
    pinCount: props.deck?.pinCount || 0,
    created_at: props.deck?.created_at || null,
    author: {
        id: UserStore.user.id,
        name: UserStore.user.name,
        username: UserStore.user.username,
        avatar: UserStore.user.avatar,
    },
    terms: props.deck?.terms || [],
});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return deck.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertTerm = (term) => {
    const termExists = deck.terms.some(existingTerm => existingTerm.id === term.id);

    if (termExists) {
        NotificationStore.addNotification('This Term is already in the Deck!', 'error');

    } else {
        deck.terms.push({
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
    deck.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    deck.terms.forEach((term, index) => {
        term.deckPivot.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    return deck.name;
});

const saveDeck = async () => {
    isSaving.value = true;

    const method = deck.id
        ? deck.patch.bind(deck)
        : deck.post.bind(deck);

    const url = deck.id
        ? route('decks.update', deck.id)
        : route('decks.store');

    method(url, {
        onSuccess: () => {
            NotificationStore.addNotification('The Deck has been saved!');
            deck.defaults();
            isSaving.value = false;
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Deck could not be saved.');
            isSaving.value = false;
        }
    });
};

// this is actually only necessary if the user is to remain on the same page, but they are currently being redirected
watch(
    () => props.deck,
    (newValue) => {
        if (newValue) {
            Object.assign(deck, newValue);
        }
    },
    {deep: true}
);

onMounted(async () => {
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
        <img src="/img/finger-back.svg" @click="router.get(route('deck-master.index', {mode: 'build'}))" alt="Back"/>
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="deck.processing || !hasNavigationGuard || !isValidRequest" label="Save"
                       @click="saveDeck"
            />
            <AppButton :disabled="deck.processing || !hasNavigationGuard" label="Reset"
                       @click="deck.reset()"
            />
            <AppButton @click="SearchStore.openSearchGenie('insert', 'terms')"
                       label="Insert Term"
            />
        </div>
    </div>

    <div class="deck-container">
        <div class="deck-container-head">
            <PinButton v-if="deck.id" modelType="deck"
                       :model="deck"/>
            <input class="deck-container-head-title" v-model="deck.name"
                   placeholder="Required: Deck Name"
            />
            <DeckActions v-if="deck.id" :model="deck"/>
            <div class="action-buttons">
                <img class="toggle" :class="['lock', { public: !deck.private }]"
                     :src="`/img/${deck.private ? 'lock.svg' : 'lock-open.svg'}`"
                     @click="deck.private = !deck.private"
                     alt="lock"/>
            </div>
        </div>

        <UserItem :user="deck.author" size="m" comment>
            <textarea class="user-comment-content" v-model="deck.description"
                      :placeholder="`Sadly, ${deck.author.name} hasn't told us anything about this Deck yet.`"
            />
            <div v-if="deck.id" class="user-comment-data">Created by {{ deck.author.name }} on
                {{ deck.created_at }}.
            </div>
        </UserItem>

        <draggable :list="deck.terms" itemKey="id"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="draggable-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete"
                         v-show="deck.terms.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>

        <div class="deck-term-count">{{ deck.terms.length }} Terms</div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
