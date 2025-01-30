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
        <div :class="['term-item-wrapper', size]">
            <div class="term-item">
                <div class="term-item-head">
                    <div class="arb">{{ data.term.term }}</div>
                    <img class="play" v-if="data.term.audio" src="/img/audio.svg" alt="play" @click="playAudio"/>
                    <div class="translit">{{ data.term.translit }}</div>
                </div>
                <div class="term-item-body">
                    <div class="eng">{{ data.term.gloss?.gloss ?? data.term.glosses[0].gloss }}</div>
                    <TermDeckToggleButton :model="data.term"/>
                </div>
                <PinButton modelType="term" :model="data.term"/>
            </div>
            <TermActions :model="data.term"/>
        </div>
    </template>
</template>
