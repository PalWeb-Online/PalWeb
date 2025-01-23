<script setup>
import {onMounted} from "vue";
import {cloneDeep} from 'lodash';
import {useStateStore} from "./stores/StateStore.js";
import {useDeckStore} from "./stores/DeckStore.js";
import Select from "./pages/Select.vue";
import Build from "./pages/Build.vue";

const StateStore = useStateStore();
const DeckStore = useDeckStore();
StateStore.data.context = 'builder';
StateStore.data.action = window.action || 'create';

onMounted(() => {
    if (StateStore.data.action === 'edit') {
        StateStore.data.step = 'build';
        DeckStore.data.user = window.user;
        DeckStore.data.stagedDeck = window.stagedDeck;
        DeckStore.data.originalDeck = cloneDeep(DeckStore.data.stagedDeck);
    }
});
</script>

<template>
    <div id="app-head">
        <button @click="StateStore.exit">Exit to Workbench</button>
        <h1>Deck Builder</h1>
        <div id="app-nav">
            <img :class="StateStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="StateStore.back"/>
            <div class="app-nav-steps">
                <div :class="{ active: StateStore.data.step === 'select' }">Select</div>
                <div :class="{ active: StateStore.data.step === 'build' }">Build</div>
            </div>
            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="StateStore.next"/>
        </div>
    </div>

    <div id="app-body">
        <div class="db-container">
            <div id="db-page-select" v-if="StateStore.data.step === 'select'">
                <Select/>
            </div>
            <div id="db-page-build" v-if="StateStore.data.step === 'build'">
                <Build/>
            </div>
        </div>
    </div>
</template>
