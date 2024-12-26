<script setup>
import {computed, onMounted} from 'vue';
import {useSpeakerStore} from "../stores/SpeakerStore.js";
import {useRecordStore} from "../stores/RecordStore.js";
import axios from "axios";

const SpeakerStore = useSpeakerStore();
const RecordStore = useRecordStore();

const audios = computed(() =>
    Object.entries(RecordStore.data.status)
        .filter(([id, status]) => status === 'done')
        .map(([id]) => RecordStore.data.records[id])
);

const playAudio = (url) => {
    const audio = new Audio(url);
    audio.play();
};

const deleteAudio = async (audio) => {
    if (confirm('Are you sure you want to delete this audio?')) {
        try {
            delete RecordStore.data.records[audio.pronunciation.id];
            delete RecordStore.data.status[audio.pronunciation.id];
            delete RecordStore.data.errors[audio.pronunciation.id];
            RecordStore.data.statusCount.done--;

            await axios.delete(`/community/audios/${audio.id}`);

        } catch (error) {
            console.error(`Error deleting audio ${audio.id}:`, error);
            alert('Failed to delete the audio.');
        }
    }
};

onMounted(() => {
});
</script>

<template>
    <div class="rw-page-title">
        <h2>Check</h2>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Check the Audios you have just published. Only the Audios published in this session of the Record Wizard
                are shown here. Go to your <a :href="`/community/audios/${ SpeakerStore.data.speaker.id }`"
                                              target="_blank">Speaker Profile</a> to see a full list of all the Audios
                you have ever published. If
                you would like to continue recording more, simply return to the <b>Queue</b> step to add more items —
                rinse & repeat!</p>
        </div>
    </div>

    <div class="rw-page__check">
        <section>
            <div class="audios-list">
                <div v-for="(audio, index) in audios" :key="index" class="pronunciation-item-wrapper inline">
                    <div class="pronunciation-item">
                        <div class="pronunciation-item-term">{{ audio.pronunciation.term }}</div>
                        <div class="pronunciation-item-phonology">
                            {{ audio.pronunciation.borrowed === true ? '(Borrowed)' : '' }}
                            {{ audio.pronunciation.translit }}
                            —
                            {{ audio.pronunciation.phonemic }}
                            {{ audio.pronunciation.phonetic }}
                        </div>
                    </div>

                    <div class="pronunciation-audios">
                        <div class="audio-item">
                            <img class="play" src="/img/audio.svg" alt="Play"
                                 @click="playAudio(audio.url)"/>
                            <img class="trash" src="/img/trash.svg" alt="Delete"
                                 @click="deleteAudio(audio)"/>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
