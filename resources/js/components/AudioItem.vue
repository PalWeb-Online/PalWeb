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
        <img v-if="!model.speaker.user.private"
             class="speaker-avatar" alt="User Avatar"
             @click="router.get(route('speaker.show', model.speaker))"
             :src="`/img/avatars/${model.speaker.user.avatar}`"/>
        <div class="audio-item-data">
            <div>by
                <Link :href="route('speaker.show', model.speaker)">
                    <template v-if="model.speaker.user.private">
                        Speaker #{{ model.speaker.id }}
                    </template>
                    <template v-else>
                        {{ model.speaker.user.name }}
                    </template>
                </Link>
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

<style scoped lang="scss">
.audio-item {
    width: 100%;
    display: flex;
    white-space: nowrap;
    font-size: 1.4rem;
    height: 3.6rem;

    .speaker-avatar {
        width: 3.6rem;
        flex-shrink: 0;
        cursor: pointer;
    }

    .audio-item-data {
        background: var(--color-pastel-light);
        padding-inline: 1.2rem;
        gap: 0.8rem;
        flex-grow: 1;
        display: flex;
        align-items: center;
        overflow: auto;
        line-height: 1;

        a {
            color: var(--color-medium-primary);
            font-weight: 700;

            &:hover {
                text-decoration: underline;
            }
        }
    }

    .audio-item-date {
        display: none;
        font-size: 1.2rem;
        font-style: italic;
    }

    button, button.audio-button {
        color: white;
        background: var(--color-medium-primary);
        font-size: 2.0rem;
        height: 100%;
        width: 3.6rem;
        border-radius: 0;
        flex-shrink: 0;
    }

    @media (min-width: 720px) {
        .audio-item-date {
            display: block;
        }
    }
}
</style>
