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

    // ContextActions
    modelType: String,

    // ModelActions
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,

    // ActionButtons
    userDecks: Object,
});

const audio = ref(null);

onMounted(() => {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/${props.term.file}.mp3`],
    });
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

    <img class="play" :src="`${imageURL}/play.svg`" @click="playAudio" alt="play"/>
    <ContextActions
        :imageURL="imageURL"
        :modelType="modelType"
        :routes="routes"
        :isUser="isUser"
        :isAdmin="isAdmin"
    />
    <TermDeckToggleButton v-if="isUser" :userDecks="userDecks" :route="routes.deckToggle" :imageURL="imageURL"/>
</template>
