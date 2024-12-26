<script setup>
import {Howl} from 'howler';
import {computed, onMounted, ref} from 'vue';
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    term: Object,
    imageURL: String,
    isPinned: Boolean,

    // ModelActions
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,

    // ActionButtons
    userDecks: Object,
});

const audio = ref(null);

onMounted(() => {
    if (props.term.audio) {
        audio.value = new Howl({
            src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${props.term.audio}`],
        });
    }
});

function playAudio() {
    if (audio.value) {
        audio.value.play();
    }
}

const gloss = computed(() => {
    return props.term.gloss.length > 48
        ? props.term.gloss.substring(0, 45) + '...'
        : props.term.gloss;
});

</script>

<template>
    <div :class="['term-item-wrapper', term.size]">
        <div class="term-item">
            <div class="term-item-head">
                <div class="arb">{{ term.term }}</div>
                <img class="play" v-if="term.audio" :src="`${imageURL}/audio.svg`" alt="play" @click="playAudio"/>
                <div class="translit">{{ term.translit }}</div>
            </div>
            <div class="term-item-body">
                <div class="eng">{{ gloss }}</div>
                <TermDeckToggleButton v-if="isUser" :userDecks="userDecks" :route="routes.deckToggle"
                                      :imageURL="imageURL"/>
            </div>
            <PinButton v-if="isUser" :isPinned="isPinned" :route="routes.pin" :imageURL="imageURL"/>
        </div>

        <ContextActions
            modelType="term"
            :imageURL="imageURL"
            :routes="routes"
            :isUser="isUser"
            :isAdmin="isAdmin"
        />
    </div>
</template>
