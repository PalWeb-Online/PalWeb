<script setup>
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/Actions/DeckActions.vue";
import LessonStatus from "../../../../components/LessonStatus.vue";
import {computed} from "vue";
import {injectDeckStudySession} from "../../../../composables/decks/useDeckStudySession.js";

const props = defineProps({
    deck: {type: Object, default: null},
    step: {type: String, default: null},
});

const DeckStudySession = injectDeckStudySession();

const deck = computed(() => props.deck ?? DeckStudySession?.data.deck ?? null);
const step = computed(() => props.step ?? DeckStudySession?.data.step ?? 'select');
</script>
<template>
    <div class="window-container">
        <div class="window-header">
            <template v-if="step !== 'select'">
                <RouterLink v-if="step === 'settings'" :to="{ name: 'deck-master.index', query: { mode: 'study' } }"
                      class="material-symbols-rounded">close
                </RouterLink>
                <button v-else @click="DeckStudySession.data.step = 'settings'" class="material-symbols-rounded">
                    arrow_back
                </button>
                <div class="window-header-url">www.palweb.app/workbench/deck-master/study/{deck}</div>
            </template>
            <template v-else>
                <div class="material-symbols-rounded">home</div>
                <div class="window-header-url">www.palweb.app/workbench/deck-master?mode=study</div>
            </template>
        </div>
        <div class="window-section-head">
            <h1>{{ $t('components.deck.title') }}</h1>
            <template v-if="deck">
                <PinButton modelType="deck" :model="deck"/>
                <DeckActions :model="deck"/>
            </template>
        </div>

        <LessonStatus v-if="deck?.lesson" :lesson="deck.lesson" :model="deck"/>
        <div class="window-content-head">
            <div class="window-content-head-title">{{ deck?.name }}</div>
        </div>
        <slot/>
    </div>
</template>
