<script setup>
import {useTerm} from "../composables/Term.js";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import TermActions from "./TermActions.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    size: {type: String, default: 'm'},
    glossId: {type: Number, default: null},
});

const {data, playAudio} = useTerm(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <PinButton modelType="term" :model="data.term"/>
        <div class="term-headword-arb">{{ data.term.term }}</div>
        <div class="term-headword-eng">({{ data.term.translit }})</div>

        <img v-if="data.term.audio" class="play" src="/img/audio.svg" @click="playAudio" alt="play"/>
        <TermActions :model="data.term"/>
        <TermDeckToggleButton :model="data.term"/>
    </template>
</template>
