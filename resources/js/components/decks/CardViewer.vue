<script setup>
import {useStateStore} from "./stores/StateStore.js";
import Select from "./pages/Select.vue";
import Study from "./pages/Study.vue";

const StateStore = useStateStore();
StateStore.data.context = 'viewer';
</script>

<template>
    <div id="app-head">
        <button @click="StateStore.exit">Exit to Workbench</button>
        <h1>Card Viewer</h1>

        <div id="app-nav">
            <img :class="StateStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="StateStore.back"/>
            <div class="app-nav-steps">
                <div :class="{ active: StateStore.data.step === 'select' }">Select</div>
                <div :class="{ active: StateStore.data.step === 'study' }">Study</div>
            </div>
            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="StateStore.next"/>
        </div>
    </div>

    <div id="app-body">
        <div class="cv-container">
            <div id="cv-page-select" v-if="StateStore.data.step === 'select'">
                <Select/>
            </div>
            <div id="cv-page-study" v-if="StateStore.data.step === 'study'">
                <Study/>
            </div>
        </div>
    </div>
</template>
