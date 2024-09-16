<script setup>
import {Howl} from 'howler';
import {onMounted, ref} from 'vue';
import ContextActions from "./ContextActions.vue";
import PinButton from "./PinButton.vue";

const props = defineProps({
    sentence: Object,
    imageURL: String,
    isPinned: Boolean,

    // ContextActions
    modelType: String,

    // ModelActions
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,
});

// const isOpen = ref(false);
// const tooltipTrigger = ref(null);
// const tooltip = ref(null);
// const {floatingStyles} = useFloating(tooltipTrigger, tooltip, {
//     placement: 'top-start',
//     middleware: [offset(), flip(), shift()]
// });

const audio = ref(null);

onMounted(() => {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/${props.sentence.file}.mp3`],
    });

    // I could show the gloss of each term in a tooltip on hover
    // tooltipTrigger.value.addEventListener('mouseenter', () => {
    //     isOpen.value = true;
    // });
    // tooltipTrigger.value.addEventListener('mouseleave', () => {
    //     isOpen.value = false;
    // });
});

function playAudio() {
    if (audio.value) {
        audio.value.play();
    }
}

</script>

<template>
    <div class="sentence-wrapper">
        <div :class="['sentence-item', sentence.size]" @click="playAudio">
            <div class="sentence-arb">
                <template v-for="term in sentence.terms">
                    <template v-if="term.slug">
                        <a :href="term.isCurrent ? '#' : routes.viewTerm.replace(':termId', term.slug)"
                           :target="term.isCurrent ? '' : '_blank'"
                           :class="['sentence-term', term.isCurrent ? 'active' : '']">
                            <div>{{ term.term }}</div>
                            <div>{{ term.translit }}</div>
                        </a>
                    </template>
                    <template v-else>
                        <div class="sentence-term">
                            <div>{{ term.term }}</div>
                            <div>{{ term.translit }}</div>
                        </div>
                    </template>
                </template>
            </div>
            <div class="sentence-eng">
                {{ sentence.trans }}
            </div>
            <PinButton v-if="isUser" :isPinned="isPinned" :route="routes.pin" :imageURL="imageURL"/>
        </div>

        <ContextActions
            :imageURL="imageURL"
            :modelType="modelType"
            :routes="routes"
            :isAdmin="isAdmin"
        />
    </div>
</template>
