<script setup>
import {useTerm} from "../composables/Term.js";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import TermActions from "./Actions/TermActions.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    glossId: {type: Number, default: null},
});

const {term, isLoading, isPlaying, playAudio} = useTerm(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="model-item-container term-item-container">
            <div class="model-item term-item">
                <PinButton modelType="term" :model="term"/>
                <div class="model-item-content">
                    <div class="term-item-gloss">
                        {{
                            glossId
                                ? term.glosses.find((gloss) => gloss.id === props.glossId).gloss
                                : term.glosses[0].gloss
                        }}
                    </div>
                    <div class="term-item-term">
                        <Link style="height: 100%; overflow: scroll; display: flex; align-items: center; gap: 1.2rem;"
                             :href="route('terms.show', term.slug)">
                            <span class="arb">{{ term.term }}</span>
                            <span class="translit">({{ term.translit }})</span>
                        </Link>
                        <button v-if="term.audio" @click="playAudio"
                                class="audio-button material-symbols-rounded" :class="{'active': isPlaying}">
                            music_note
                        </button>
                    </div>
                </div>
                <TermDeckToggleButton :model="term"/>
                <TermActions :model="term"/>
            </div>
        </div>
    </template>
</template>
