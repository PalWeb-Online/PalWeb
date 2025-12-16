<script setup>
import {ref, watch} from "vue";
import {route} from 'ziggy-js';
import {Howl} from "howler";
import {useUserStore} from "../stores/UserStore.js";
import {router} from "@inertiajs/vue3";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const audio = ref(null);
const isPlaying = ref(false);

function loadAudio() {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${props.model.filename}`],
        onplay: () => isPlaying.value = true,
        onend: () => isPlaying.value = false,
        onstop: () => isPlaying.value = false,
        onpause: () => isPlaying.value = false,
    });
}

function playAudio() {
    if (audio.value) {
        audio.value.play();
    }
}

const deleteAudio = () => {
    if (!confirm('Are you sure you want to delete this Audio?')) return;

    router.delete(route('audios.destroy', props.model.id), {preserveScroll: true});
};

watch(() => props.model, loadAudio, {immediate: true});
</script>

<template>
    <div class="audio-item">
        <button @click="playAudio"
                class="audio-button material-symbols-rounded" :class="{'active': isPlaying}">
            music_note
        </button>

        <div class="audio-item-data">
            <div class="audio-item-speaker">
                <template v-if="model.speaker.user.private">
                    <div>by
                        <Link :href="route('speaker.show', model.speaker)">Speaker #{{ model.speaker.id }}</Link>
                    </div>
                </template>
                <template v-else>
                    <div>by
                        <Link :href="route('speaker.show', model.speaker)">{{ model.speaker.user.name }}</Link>
                    </div>
                    <img class="avatar" alt="User Avatar" :src="`/img/avatars/${model.speaker.user.avatar}`"/>
                </template>
            </div>

            <div class="audio-item-info">
                {{ model.speaker.fluency_alias }}
                <span style="text-transform: capitalize">{{
                        model.speaker.gender !== 'other' ? model.speaker.gender : ''
                    }}</span>
                Speaker from {{ model.speaker.location.name_ar }} ({{ model.speaker.location.name_en }})
            </div>
            <div class="audio-item-date">
                {{ model.created_at }}
            </div>
        </div>

        <button v-if="UserStore.user?.id === model.speaker.user.id || UserStore.isAdmin"
                @click="deleteAudio" class="material-symbols-rounded">delete
        </button>
    </div>
</template>
