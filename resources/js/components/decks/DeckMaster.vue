<script setup>
import {onMounted} from "vue";
import {useStateStore} from "./stores/StateStore.js";
import {useDeckStore} from "./stores/DeckStore.js";
import Select from "./pages/Select.vue";
import Build from "./pages/Build.vue";
import Study from "./pages/Study.vue";

const StateStore = useStateStore();
const DeckStore = useDeckStore();

const toggleMode = async () => {
    StateStore.data.step = 'select';
    StateStore.data.mode = StateStore.data.mode === 'build' ? 'study' : 'build';

    await DeckStore.setDecks();
}

onMounted(() => {
    if (window.stagedDeck) {
        StateStore.data.step = 'build';
        DeckStore.data.stagedDeck = window.stagedDeck;
    }
});
</script>

<template>
    <div id="app-head">
        <a href="/workbench">Exit to Workbench</a>
        <h1>Deck Master</h1>
        <div id="app-nav">
            <div @click="toggleMode()" id="dm-mode-toggle" :class="StateStore.data.mode">
                <div class="dm-mode-toggle-slider">{{ StateStore.data.mode }}</div>
            </div>
            <!--            <img :class="StateStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"-->
            <!--                 @click="StateStore.back"/>-->
            <!--            <div class="app-nav-steps">-->
            <!--                <div :class="{ active: StateStore.data.step === 'select' }">Select</div>-->
            <!--                <div v-if="StateStore.data.mode === 'build'" :class="{ active: StateStore.data.step === 'build' }">-->
            <!--                    Build-->
            <!--                </div>-->
            <!--                <div v-if="StateStore.data.mode === 'study'" :class="{ active: StateStore.data.step === 'study' }">-->
            <!--                    Study-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"-->
            <!--                 @click="StateStore.next"/>-->
        </div>
    </div>

    <div id="app-body" :class="{ 'dm-mode-study': StateStore.data.mode === 'study' }">
        <div class="db-container">
            <div id="db-page-select" v-if="StateStore.data.step === 'select'">
                <Select/>
            </div>
            <template v-if="StateStore.data.mode === 'build'">
                <div id="db-page-build" v-if="StateStore.data.step === 'build'">
                    <Build/>
                </div>
            </template>
            <template v-if="StateStore.data.mode === 'study'">
                <div id="cv-page-study" v-if="StateStore.data.step === 'study'">
                    <Study/>
                </div>
            </template>
        </div>
    </div>
</template>
