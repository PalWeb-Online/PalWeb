<script setup>
import {route} from 'ziggy-js';
import AudioItem from "./AudioItem.vue";
import {onMounted, reactive, ref} from "vue";

const props = defineProps({
    model: Object,
    audio: Object,
});

const pronunciation = reactive({});
const showAudios = ref(false);
const audiosFetched = ref(false);

const fetchAudios = async () => {
    try {
        const response = await axios.get(route('terms.get.pronunciations.audios', {pronunciation: props.model.id}));
        const audios = response.data.filter(audio =>
            !props.model.audios.some(existing => existing.id === audio.id)
        );

        pronunciation.audios.push(...audios);
        audiosFetched.value = true;
        showAudios.value = true;

    } catch (error) {
        console.error('Error fetching Audios:', error);
    }
}

onMounted(() => {
    Object.assign(pronunciation, props.model);
});
</script>

<template>
    <div class="model-item-container pronunciation-item-container">
        <div class="pronunciation-item">
            <div class="pronunciation-item-data">
                <div class="pronunciation-item-dialect">{{ model.dialect.name }}</div>
                <div class="pronunciation-item-phonology">
                    {{ model.borrowed === true ? '(Borrowed)' : '' }}
                    {{ model.translit }}
                    —
                    {{ model.phonemic }}
                    {{ model.phonetic }}
                </div>
            </div>
            <Link v-if="audio" class="pronunciation-item-term"
                  :href="route('terms.show', model.term.slug)">{{ model.term.term }}
            </Link>
        </div>

        <div v-if="audio" class="pronunciation-audios">
            <AudioItem :model="audio"/>
        </div>
        <div v-else-if="model.audios.length > 0" class="pronunciation-audios">
            <AudioItem v-if="showAudios" v-for="audio in model.audios" :model="audio"/>
            <AudioItem v-else :model="model.audios[0]"/>
        </div>

        <button v-if="!audio && model.audios_count > 1 && !audiosFetched" @click="fetchAudios">+
        </button>
        <button v-if="!audio && model.audios_count > 1 && audiosFetched && showAudios" @click="showAudios = false">-
        </button>
        <button v-if="!audio && model.audios_count > 1 && audiosFetched && !showAudios" @click="showAudios = true">+
        </button>
    </div>
</template>
