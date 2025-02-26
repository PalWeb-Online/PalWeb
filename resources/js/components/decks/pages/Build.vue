<script setup>
import {onMounted, watch} from "vue";
import {cloneDeep} from "lodash";
import draggable from 'vuedraggable';
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import TermItem from "../ui/TermItem.vue";
import AppButton from "../../AppButton.vue";
import AppAlert from "../../AppAlert.vue";
import PinButton from "../../PinButton.vue";
import DeckActions from "../../DeckActions.vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import {useSearchGenie} from '../../../composables/useSearchGenie.js';
import {useSearchStore} from "../../../stores/SearchStore.js";

useSearchGenie('insert');
const SearchStore = useSearchStore();

const NotificationStore = useNotificationStore();
const StateStore = useStateStore();
const DeckStore = useDeckStore();

const { showAlert, handleConfirm, handleCancel } = useNavGuard(StateStore);

const insertTerm = (term) => {
    const termExists = DeckStore.data.stagedDeck.terms.some(existingTerm => existingTerm.id === term.id);

    if (termExists) {
        NotificationStore.addNotification('This Term is already in the Deck!', 'error');

    } else {
        DeckStore.data.stagedDeck.terms.push({
            id: term.id,
            term: term.term,
            category: term.category,
            translit: term.translit,
            glosses: term.glosses.map((gloss) => ({
                id: gloss.id,
                gloss: gloss.gloss,
            })),
            gloss_id: term.glosses[0].id,
            position: '',
        });
        updatePosition();
        NotificationStore.addNotification(`Added ${term.term} to the Deck!`);
    }
}

const removeTerm = (index) => {
    DeckStore.data.stagedDeck.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    DeckStore.data.stagedDeck.terms.forEach((term, index) => {
        term.position = index + 1;
    });
}

const saveDeck = async () => {
    const success = await DeckStore.saveDeck();

    if (success) {
        NotificationStore.addNotification('Your Deck has been saved!');
    } else {
        NotificationStore.addNotification(StateStore.data.errorMessage, 'error');
    }
};

onMounted(async () => {
    DeckStore.data.originalDeck = cloneDeep(DeckStore.data.stagedDeck);

    watch(
        () => SearchStore.selectedModel,
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
        <img src="/img/reverse.svg" @click="StateStore.toSelect" alt="Back"/>
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="!StateStore.hasNavigationGuard || !StateStore.isValidRequest" label="Save"
                       @click="saveDeck"
            />
            <AppButton :disabled="!StateStore.hasNavigationGuard" label="Reset"
                       @click="DeckStore.resetDeck"
            />
        </div>
    </div>

    <div class="deck-container">
        <div class="deck-container-head">
            <PinButton v-if="DeckStore.data.stagedDeck.id" modelType="deck" :model="DeckStore.data.stagedDeck"/>
            <input class="deck-container-head-title" v-model="DeckStore.data.stagedDeck.name"
                   placeholder="Required: Deck Name"
            />
            <img :class="['lock', { public: !DeckStore.data.stagedDeck.private }]"
                 :src="`/img/${DeckStore.data.stagedDeck.private ? 'lock.svg' : 'lock-open.svg'}`"
                 @click="DeckStore.data.stagedDeck.private = !DeckStore.data.stagedDeck.private" alt="lock"/>
            <DeckActions v-if="DeckStore.data.stagedDeck.id" :model="DeckStore.data.stagedDeck"/>
        </div>
        <div class="user-wrapper">
            <div class="user-avatar">
                <img :src="`/img/avatars/${DeckStore.data.stagedDeck.author.avatar}`" alt="Profile Picture"/>
            </div>
            <div class="user-comment">
                <div class="user-comment-head">
                    <div>{{ DeckStore.data.stagedDeck.author.name }}</div>
                    <div>({{ DeckStore.data.stagedDeck.author.username }})</div>
                </div>
                <div class="user-comment-body">
                    <textarea class="user-comment-body-content" id="deck[description]"
                              v-model="DeckStore.data.stagedDeck.description"
                              name="deck[description]" placeholder="(Optional) Tell us something about this Deck."
                    />
                </div>
            </div>
        </div>

        <draggable :list="DeckStore.data.stagedDeck.terms" itemKey="id"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="db-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete" v-show="DeckStore.data.stagedDeck.terms.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>

        <div class="deck-term-count">{{ DeckStore.data.stagedDeck.terms.length }} Terms</div>
    </div>

    <AppAlert
        v-if="showAlert"
        message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
        @confirm="handleConfirm"
        @cancel="handleCancel"
    />
</template>
