<script setup>
import {Howl} from 'howler';
import {computed, nextTick, onBeforeUnmount, onMounted, ref} from 'vue';
import {flip, offset, shift, useFloating} from "@floating-ui/vue";
import TermActions from "./TermActions.vue";
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

const isOpenTooltip = ref(false);
const tooltipTrigger = ref(null);
const tooltip = ref(null);
const {floatingStyles} = useFloating(tooltipTrigger, tooltip, {
    placement: 'right',
    middleware: [offset({
        mainAxis: 4,
        crossAxis: -1,
    }), flip(), shift()]
});

const audio = ref(null);

onMounted(() => {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/${props.term.file}.mp3`],
    });

    tooltipTrigger.value.addEventListener('mouseenter', () => {
        isOpenTooltip.value = true;
    });
    tooltipTrigger.value.addEventListener('mouseleave', () => {
        isOpenTooltip.value = false;
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


const isOpen = ref(false);
const trigger = ref(null);
const menu = ref(null);
const clickX = ref(0);
const clickY = ref(0);

const toggleMenu = async (event) => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        clickX.value = event.clientX;
        clickY.value = event.clientY;

        await nextTick(() => {
            const element = menu.value;
            if (element) {
                const rect = element.getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;

                if (clickX.value + rect.width > viewportWidth) {
                    clickX.value = viewportWidth - rect.width - 8;
                }
                if (clickY.value + rect.height > viewportHeight) {
                    clickY.value = viewportHeight - rect.height - 8;
                }
            }
        });

        document.body.style.overflow = 'hidden';
        document.addEventListener('click', handleClickOutside);

    } else {
        document.body.style.overflow = '';
        document.removeEventListener('click', handleClickOutside);
    }
};

const handleClickOutside = (event) => {
    if (!trigger.value.contains(event.target) && !menu.value.contains(event.target)) {
        isOpen.value = false;
        document.body.style.overflow = '';
        document.removeEventListener('click', handleClickOutside);
    }
};

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

</script>

<template>
    <div :class="['term-item-wrapper', term.size]">
        <div class="term-item">
            <div class="arb" ref="trigger" @click="toggleMenu">{{ term.term }}</div>
            <div class="eng">{{ gloss }}</div>

            <div class="popup-menu" ref="menu" v-if="isOpen"
                 :style="{ top: `${clickY}px`, left: `${clickX}px`, position: 'fixed' }">
                <TermActions
                    modelType="term"
                    :routes="routes"
                    :isUser="isUser"
                    :isAdmin="isAdmin"
                />
            </div>

            <PinButton v-if="isUser" :isPinned="isPinned" :route="routes.pin" :imageURL="imageURL"/>
            <TermDeckToggleButton v-if="isUser" :userDecks="userDecks" :route="routes.deckToggle" :imageURL="imageURL"/>
        </div>

        <img class="play" ref="tooltipTrigger" :src="`${imageURL}/play.svg`" alt="play" @click="playAudio"/>
        <div ref="tooltip" v-if="isOpenTooltip" :style="floatingStyles" class="notification">
            {{ term.translit }}
        </div>
    </div>
</template>
