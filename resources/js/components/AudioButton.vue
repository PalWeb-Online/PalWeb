<script setup>
import {computed, onMounted, ref} from "vue";
import {useAudio} from "../composables/Audio.js";
import AppTooltip from "./AppTooltip.vue";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    pronunciation: {
        type: Object,
        required: true
    }
})

const isUserAudio = computed(() =>
    UserStore.user?.dialects.includes(props.pronunciation.dialect.id)
);

const appTooltip = ref(null);

const {isPlaying, createAudio, playAudio} = useAudio(props);

onMounted(() => {
    createAudio(props.pronunciation.audios[0].filename);
});

const showDialectTooltip = (event) => {
    if (!isUserAudio.value) {
        appTooltip.value?.showTooltip(props.pronunciation.dialect.name, event);
    }
};
</script>

<template>
    <button @click="playAudio"
            class="audio-button material-symbols-rounded" :class="{'active': isPlaying}"
            @mousemove="showDialectTooltip"
            @mouseleave="appTooltip.hideTooltip()"
    >
        music_note
        <span v-if="UserStore.user && !isUserAudio">change_circle</span>
    </button>
    <AppTooltip ref="appTooltip"/>
</template>

<style scoped lang="scss">

</style>
