<script setup>
import {computed, onMounted, watch} from "vue";
import {cloneDeep} from "lodash";
import draggable from 'vuedraggable';
import {useDeckMasterStore} from "../stores/DeckMasterStore.js";
import TermItem from "../ui/TermItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/DeckActions.vue";
import AppAlert from "../../../../components/AppAlert.vue";
import {Inertia} from "@inertiajs/inertia";

const SearchStore = useSearchStore();

const NotificationStore = useNotificationStore();
const DeckMasterStore = useDeckMasterStore();

const hasNavigationGuard = computed(() => {
    return JSON.stringify(DeckMasterStore.data.stagedDeck) !== JSON.stringify(DeckMasterStore.data.originalDeck)
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertTerm = (term) => {
    const termExists = DeckMasterStore.data.stagedDeck.terms.some(existingTerm => existingTerm.id === term.id);

    if (termExists) {
        NotificationStore.addNotification('This Term is already in the Deck!', 'error');

    } else {
        DeckMasterStore.data.stagedDeck.terms.push({
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
    DeckMasterStore.data.stagedDeck.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    DeckMasterStore.data.stagedDeck.terms.forEach((term, index) => {
        term.deckPivot.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    return DeckMasterStore.data.stagedDeck.name;
});

const saveDeck = async () => {
    // todo: the problem with this is that if the request fails, you can't try again at all.
    DeckMasterStore.data.originalDeck = cloneDeep(DeckMasterStore.data.stagedDeck);

    if (DeckMasterStore.data.stagedDeck.id) {
        Inertia.patch(route('decks.update', DeckMasterStore.data.stagedDeck.id), {deck: DeckMasterStore.data.stagedDeck},
            {
                onSuccess: () => {
                    NotificationStore.addNotification('The Deck has been saved!');
                },
                onError: () => {
                    NotificationStore.addNotification('Oh no! The Deck could not be saved.');
                }
            }
        );

    } else {
        Inertia.post(route('decks.store'), {deck: DeckMasterStore.data.stagedDeck},
            {
                onSuccess: () => {
                    NotificationStore.addNotification('The Deck has been saved!');
                },
                onError: () => {
                    NotificationStore.addNotification('Oh no! The Deck could not be saved.');
                }
            }
        );
    }
};

const resetDeck = async () => {
    DeckMasterStore.data.stagedDeck = cloneDeep(DeckMasterStore.data.originalDeck);
};

onMounted(async () => {
    DeckMasterStore.data.originalDeck = cloneDeep(DeckMasterStore.data.stagedDeck);

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
        <img src="/img/reverse.svg" @click="DeckMasterStore.toSelect" alt="Back"/>
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="!hasNavigationGuard || !isValidRequest" label="Save"
                       @click="saveDeck"
            />
            <AppButton :disabled="!hasNavigationGuard" label="Reset"
                       @click="resetDeck"
            />
            <AppButton label="Insert Term"
                       @click="SearchStore.openSearchGenie('insert', 'terms')"
            />
        </div>
    </div>

    <div class="deck-container">
        <div class="deck-container-head">
            <PinButton v-if="DeckMasterStore.data.stagedDeck.id" modelType="deck"
                       :model="DeckMasterStore.data.stagedDeck"/>
            <input class="deck-container-head-title" v-model="DeckMasterStore.data.stagedDeck.name"
                   placeholder="Required: Deck Name"
            />
            <img :class="['lock', { public: !DeckMasterStore.data.stagedDeck.private }]"
                 :src="`/img/${DeckMasterStore.data.stagedDeck.private ? 'lock.svg' : 'lock-open.svg'}`"
                 @click="DeckMasterStore.data.stagedDeck.private = !DeckMasterStore.data.stagedDeck.private"
                 alt="lock"/>
            <DeckActions v-if="DeckMasterStore.data.stagedDeck.id" :model="DeckMasterStore.data.stagedDeck"/>
        </div>
        <div class="user-wrapper">
            <div class="user-avatar">
                <img :src="`/img/avatars/${DeckMasterStore.data.stagedDeck.author.avatar}`" alt="Profile Picture"/>
            </div>
            <div class="user-comment">
                <div class="user-comment-head">
                    <div>{{ DeckMasterStore.data.stagedDeck.author.name }}</div>
                    <div>({{ DeckMasterStore.data.stagedDeck.author.username }})</div>
                </div>
                <div class="user-comment-body">
                    <textarea class="user-comment-body-content" id="deck[description]"
                              v-model="DeckMasterStore.data.stagedDeck.description"
                              name="deck[description]" placeholder="(Optional) Tell us something about this Deck."
                    />
                </div>
            </div>
        </div>

        <draggable :list="DeckMasterStore.data.stagedDeck.terms" itemKey="id"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="db-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete"
                         v-show="DeckMasterStore.data.stagedDeck.terms.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>

        <div class="deck-term-count">{{ DeckMasterStore.data.stagedDeck.terms.length }} Terms</div>
    </div>

    <AppAlert
        v-if="showAlert"
        message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
        @confirm="handleConfirm"
        @cancel="handleCancel"
    />
</template>
