<script setup>
import {route} from "ziggy-js";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/Actions/DeckActions.vue";
import {useDeckStudyStore} from "../Stores/DeckStudyStore.js";
import LessonStatus from "../../../../components/LessonStatus.vue";

const DeckStudyStore = useDeckStudyStore();
</script>
<template>
    <div class="window-container">
        <div class="window-header">
            <template v-if="DeckStudyStore.data.step !== 'select'">
                <Link v-if="DeckStudyStore.data.step === 'settings'" :href="route('deck-master.index', {mode: 'study'})"
                      class="material-symbols-rounded">close
                </Link>
                <Link v-else :href="route('deck-master.study', DeckStudyStore.data.deck.id)" class="material-symbols-rounded">
                    arrow_back
                </Link>
                <div class="window-header-url">www.palweb.app/workbench/deck-master/study/{deck}</div>
            </template>
            <template v-else>
                <div class="material-symbols-rounded">home</div>
                <div class="window-header-url">www.palweb.app/workbench/deck-master?mode=study</div>
            </template>
        </div>
        <div class="window-section-head">
            <h1>deck</h1>
            <template v-if="DeckStudyStore.data.deck">
                <PinButton modelType="deck" :model="DeckStudyStore.data.deck"/>
                <DeckActions :model="DeckStudyStore.data.deck"/>
            </template>
        </div>

        <LessonStatus v-if="DeckStudyStore.data.deck?.lesson" :lesson="DeckStudyStore.data.deck.lesson" :model="DeckStudyStore.data.deck"/>
        <div class="window-content-head">
            <div class="window-content-head-title">{{ DeckStudyStore.data.deck?.name }}</div>
        </div>
        <slot/>
    </div>
</template>
