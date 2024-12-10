<script setup>
import {computed, onMounted} from 'vue';
import {useRecordStore} from "../store/RecordStore.js";
import axios from "axios";

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

            await axios.delete(`/api/audio/${audio.id}`);

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
    <div class="wizard-page-title">
        <h2>Review</h2>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Review the Audios you have just published. If you'd like to continue recording, simply return to the
                Queue step & another 100 Pronunciations will be added to the recording queue. However, the maximum
                number of Audios that may be uploaded in one session is 500; simply refresh the Record Wizard to
                continue past that.</p>
        </div>
    </div>

    <div class="wizard-section-container">
        <section>
            <div id="wizard-queue">
                <li v-for="(audio, index) in audios" :key="index">
                    <div>
                        <img
                            class="audio" src="/img/play.svg" alt="Play"
                            @click="playAudio(audio.url)"
                        />
                        <div>
                            {{ index + 1 }}. <span>{{ audio.pronunciation.term }}</span> ({{
                                audio.pronunciation.translit
                            }})
                        </div>
                    </div>
                    <!--                    TODO: Link to page in Dictionary -->

                    <img
                        class="trash" src="/img/trash.svg" alt="Delete"
                        @click="deleteAudio(audio)"
                    />
                </li>
            </div>
        </section>
    </div>
</template>
