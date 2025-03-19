<script setup>
import {ref, watch} from "vue";
import {route} from 'ziggy-js';
import {Howl} from "howler";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const audio = ref(null);

function loadAudio() {
    audio.value = new Howl({
        src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${props.model.filename}`],
    });
}

function playAudio() {
    if (audio.value) {
        audio.value.play();
    }
}

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

watch(() => props.model, loadAudio, {immediate: true});
</script>

<template>
    <div class="audio-item">
        <img class="play" src="/img/audio.svg" alt="Play"
             @click="playAudio"/>

        <div class="mini-user-profile">
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

        <template v-if="UserStore.user?.id === model.speaker.user.id">
            <form :action="route('audios.destroy', model.id)" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" :value="csrfToken">
                <img
                    onclick="if (confirm('Are you sure you want to delete this Audio?')) this.closest('form').submit()"
                    class="trash" alt="Delete"
                    src="/img/trash.svg"/>
            </form>
        </template>
    </div>
</template>
