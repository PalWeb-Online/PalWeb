<script setup>
import {computed} from 'vue';
import {useRecordStore} from "../stores/RecordStore.js";
import {useRecordWizardStore} from "../stores/RecordWizardStore.js";
import {route} from "ziggy-js";
import AppTip from "../../../../components/AppTip.vue";
import PronunciationItem from "../../../../components/PronunciationItem.vue";

const RecordStore = useRecordStore();
const RecordWizardStore = useRecordWizardStore();

const audios = computed(() =>
    Object.entries(RecordStore.data.status)
        .filter(([id, status]) => status === 'done')
        .map(([id]) => RecordStore.data.records[id])
);
</script>

<template>
    <div class="window-section-head">
        <h2>Check</h2>
    </div>
    <AppTip>
        <p>Check the Audios you have just published. Only the Audios published in this session of the Record Wizard
            are shown here. Go to <a :href="route('speaker.show', RecordWizardStore.speaker.id)" target="_blank">
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
