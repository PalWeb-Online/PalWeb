<script setup>
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";
import {router} from "@inertiajs/vue3";
import {useAudio} from "../composables/audios/useAudio.js";

const UserStore = useUserStore();
const {deleteAudio} = useAudio();

const props = defineProps({
    model: Object,
});

const {isPlaying, playAudio} = useAudio(props.model.url);
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
             :src="model.speaker.user.avatar_url"/>
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
                @click="deleteAudio(model)" class="material-symbols-rounded">delete
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

    button:not(.audio-button) {
        color: white;
        background: var(--color-medium-primary);
    }

    button {
        font-size: 2.0rem;
        height: 100%;
        width: 3.6rem;
        border-radius: 0;
        flex-shrink: 0;
    }

    @media (width >= 720px) {
        .audio-item-date {
            display: block;
        }
    }
}
</style>
