<script setup>
import {Howl} from 'howler';
import {onMounted, ref} from 'vue';
import ContextActions from "./ContextActions.vue";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";

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
            src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audio/${props.term.audio}.mp3`],
        });
    }
});

function playAudio() {
    if (audio.value) {
        audio.value.play();
    }
}
</script>

<template>
    <PinButton v-if="isUser" :isPinned="isPinned" :route="routes.pin" :imageURL="imageURL"/>
    <div class="term-headword-arb">{{ term.term }}</div>
    <div class="term-headword-eng">({{ term.translit }})</div>

    <img v-if="term.audio" class="play" :src="`${imageURL}/audio.svg`" @click="playAudio" alt="play"/>
    <ContextActions
        modelType="term"
        :imageURL="imageURL"
        :routes="routes"
        :isUser="isUser"
        :isAdmin="isAdmin"
    />
    <TermDeckToggleButton v-if="isUser" :userDecks="userDecks" :route="routes.deckToggle" :imageURL="imageURL"/>
</template>
