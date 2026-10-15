<script setup>
import {computed} from 'vue';
import {useSoundBoothSession} from "../useSoundBoothSession.js";
import AppTip from "../../../../components/AppTip.vue";
import PronunciationItem from "../../../../components/PronunciationItem.vue";

const session = useSoundBoothSession();

const audios = computed(() =>
    Object.entries(session.recordings.status)
        .filter(([id, status]) => status === 'done')
        .map(([id]) => session.recordings.items[id])
);
</script>

<template>
    <div class="window-section-head">
        <h2>{{ $t('sound-booth.check') }}</h2>
    </div>
    <AppTip>
        <p>Check the Audios you have just published. Only the Audios published in this session of the Sound Booth
            are shown here. Go to <a :href="route('speaker.show', session.speaker.id)" target="_blank">
                your Speaker profile</a> to see a full list of all the Audios you have ever published. If you would like
            to continue recording more, simply return to the <b>Queue</b> step to add more items — rinse & repeat!</p>
    </AppTip>

    <div class="rw-page__check">
        <section>
            <div class="model-list index-list">
                <PronunciationItem v-for="audio in audios" :model="audio.pronunciation" :audio="audio"/>
            </div>
        </section>
    </div>
</template>
