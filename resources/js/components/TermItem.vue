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
    glossId: {type: Number, default: null},
});

const {term, isLoading, playAudio} = useTerm(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="term-item-wrapper">
            <div class="term-item">
                <div class="term-item-head">
                    <div class="arb">{{ term.term }}</div>
                    <img class="play" v-if="term.audio" src="/img/audio.svg" alt="play" @click="playAudio"/>
                    <div class="translit">{{ term.translit }}</div>
                </div>
                <div class="term-item-body">
                    <div class="eng">{{ glossId ? term.glosses.find((gloss) => gloss.id === props.glossId).gloss : term.glosses[0].gloss }}</div>
                    <TermDeckToggleButton :model="term"/>
                </div>
                <PinButton modelType="term" :model="term"/>
            </div>
            <TermActions :model="term"/>
        </div>
    </template>
</template>
