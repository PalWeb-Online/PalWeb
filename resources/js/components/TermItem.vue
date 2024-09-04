<script setup>
import {Howl} from 'howler';
import {computed, onMounted, ref} from 'vue';
import ContextActions from "./ContextActions.vue";
import PinButton from "./PinButton.vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

const props = defineProps({
    term: Object,
    pinURL: String,

    // ContextActions
    modelType: String,
    triggerURL: String,

    // ModelActions
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,

    // ActionButtons
    isPinned: Boolean,
    userDecks: Object,
});

const isOpen = ref(false);
const tooltipTrigger = ref(null);
const tooltip = ref(null);
const {floatingStyles} = useFloating(tooltipTrigger, tooltip, {
    placement: 'top-start',
    middleware: [offset(), flip(), shift()]
});

const audio = ref(null);

onMounted(() => {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/${props.term.file}.mp3`],
    });

    tooltipTrigger.value.addEventListener('mouseenter', () => {
        isOpen.value = true;
    });
    tooltipTrigger.value.addEventListener('mouseleave', () => {
        isOpen.value = false;
    });
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
    <div class="term-li-wrapper">
        <ContextActions
            :modelType="modelType"
            :triggerURL="triggerURL"
            :routes="routes"
            :isUser="isUser"
            :isAdmin="isAdmin"
            :isPinned="isPinned"
            :userDecks="userDecks"
        />

        <div class="term-li">
            <div ref="tooltipTrigger" class="arb audio" @click="playAudio">
                {{ term.term }}
            </div>
            <div ref="tooltip" v-if="isOpen" :style="floatingStyles" class="notification">
                {{ term.translit }}
            </div>

            <div class="eng">
                {{ gloss }}
            </div>

            <PinButton v-if="isUser" :isPinned="isPinned" :pinRoute="routes.pin" :pinURL="pinURL"/>
        </div>
    </div>
</template>
