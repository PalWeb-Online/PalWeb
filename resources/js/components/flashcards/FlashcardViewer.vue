<script setup>
import {useStateStore} from "./stores/StateStore.js";
import Decks from "./pages/Decks.vue";
import Cards from "./pages/Cards.vue";
import SearchGenie from "../search/SearchGenie.vue";

const StateStore = useStateStore();
</script>

<template>
    <div id="app-head">
        <button @click="StateStore.exit">Exit to Workbench</button>
        <h1>Flashcards</h1>

        <div id="app-nav">
            <img :class="StateStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="StateStore.back"/>
            <div class="app-nav-steps">
                <div :class="{ active: StateStore.data.step === 'decks' }">Decks</div>
                <div :class="{ active: StateStore.data.step === 'cards' }">Cards</div>
            </div>
            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="StateStore.next"/>
        </div>
    </div>

    <div id="app-body">
        <div class="fv-container">
            <div id="fv-page-decks" v-if="StateStore.data.step === 'decks'">
                <Decks/>
            </div>
            <div id="fv-page-cards" v-if="StateStore.data.step === 'cards'">
                <Cards/>
            </div>
        </div>
    </div>
</template>
