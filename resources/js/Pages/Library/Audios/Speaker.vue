<script setup>
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import {route} from "ziggy-js";
import AppTip from "../../../components/AppTip.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import UserItem from "../../../components/UserItem.vue";

const UserStore = useUserStore();

const props = defineProps({
    speaker: Object,

    // todo: why is this an object?
    audios: Object
});

defineOptions({
    layout: Layout
});
</script>
<template>
    <Head :title="`Library: Audios by ${speaker.user.name}`"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('audios.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/audios/{speaker}</div>
            </div>
            <div class="window-section-head">
                <h1>speaker</h1>
            </div>
            <AppTip v-if="speaker.user.id === UserStore.user.id && speaker.user.private">
                <p>Your Profile is currently set to Private. Others may still interact with any Audios you have
                    recorded, but your Speaker profile is anonymous & does not link to your user account.</p>
            </AppTip>

            <UserItem :user="speaker.user" size="l" :speaker="speaker">
                <SpeakerItem :speaker="speaker"/>
            </UserItem>

            <div class="window-section-head">
                <h2>audios</h2>
            </div>
            <template v-if="audios.data.length > 0">
                <div class="model-list index-list" style="padding: 3.2rem 1.6rem">
                    <PronunciationItem v-for="audio in audios.data" :model="audio.pronunciation" :audio="audio"/>
                </div>
                <Paginator :links="audios.meta.links"/>
            </template>
            <template v-else>
                <AppTip>
                    <p>{{ speaker.user.private ? 'This Speaker' : speaker.user.name }} has not uploaded any Audios
                        yet.</p>
                </AppTip>
            </template>
        </div>
    </div>
</template>
