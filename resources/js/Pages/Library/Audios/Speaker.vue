<script setup>
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import SpeakerContainer from "../../../components/SpeakerContainer.vue";
import {route} from "ziggy-js";
import AppTip from "../../../components/AppTip.vue";

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
    <div id="app-head">
        <Link :href="route('audios.index')"><h1>Audios</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link :href="route('audios.index')"><- to All</Link>
        </div>
        <SpeakerContainer :speaker="speaker"/>

        <template v-if="audios.data.length > 0">
            <div class="audios-list">
                <PronunciationItem v-for="audio in audios.data" :model="audio.pronunciation" :audio="audio"/>
            </div>
            <Paginator :links="audios.meta.links"/>
        </template>
        <template v-else>
            <AppTip>
                <p>{{ speaker.user.private ? 'This Speaker' : speaker.user.name }} has not uploaded any Audios yet.</p>
            </AppTip>
        </template>
    </div>
</template>
