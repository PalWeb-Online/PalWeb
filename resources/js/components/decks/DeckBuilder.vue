<script setup>
import {useStateStore} from "./stores/StateStore.js";
import Select from "./pages/Select.vue";
import Build from "./pages/Build.vue";
import AppDialog from "../AppDialog.vue";

const StateStore = useStateStore();
StateStore.data.context = 'builder';
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

        <AppDialog title="Deck Builder" size="large">
            <template #trigger>
                <img src="/img/idea.svg" alt="Info"/>
            </template>
            <template #content>
                <p>Welcome to the <b>Deck Builder</b>! Use this form to create a new <b>Deck</b>. By creating a
                    <b>Deck</b>, you'll be able to group terms from the Dictionary in any way you want. You'll be able
                    to study them as Flashcards & share them with others!
                </p>
            </template>
        </AppDialog>
    </div>

</template>
