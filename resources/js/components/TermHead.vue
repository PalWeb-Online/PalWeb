<script setup>
import {Howl} from 'howler';
import {onMounted, ref} from 'vue';
import ContextActions from "./ContextActions.vue";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";

const props = defineProps({
    term: Object,
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
</script>

<template>
    <PinButton v-if="isUser"
               :isPinned="isPinned"
               :route="routes.pin"
    />
    <div class="term-headword-arb">{{ term.term }}</div>
    <div class="term-headword-eng">({{ term.translit }})</div>

    <img v-if="term.audio" class="play" src="/img/audio.svg" @click="playAudio" alt="play"/>
    <ContextActions
        modelType="term"
        :routes="routes"
        :isUser="isUser"
        :isAdmin="isAdmin"
    />
    <TermDeckToggleButton v-if="isUser"
                          :userDecks="userDecks"
                          :route="routes.deckToggle"
    />
</template>
