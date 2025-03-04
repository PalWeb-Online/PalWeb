<script setup>
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3';
import AudioItem from "./AudioItem.vue";

const props = defineProps({
    model: Object,
    audio: Object,
});

const fetchAudios = async () => {
    try {
        const response = await axios.get(route('terms.get.pronunciations.audios', {pronunciation: props.model.id}));
        const audios = response.data.filter(audio =>
            !props.model.audios.some(existing => existing.id === audio.id)
        );

        props.model.audios.push(...audios);

    } catch (error) {
        console.error('Error fetching Audios:', error);
    }
}
</script>

<template>
    <div class="pronunciation-item-wrapper">
        <div class="pronunciation-item">
            <Link v-if="audio" class="pronunciation-item-term"
                  :href="route('terms.show', model.term.slug)">{{ model.term.term }}
            </Link>
            <div class="pronunciation-item-phonology">
                {{ model.borrowed === true ? '(Borrowed)' : '' }}
                {{ model.translit }}
                —
                {{ model.phonemic }}
                {{ model.phonetic }}
            </div>
            <div class="pronunciation-item-dialect">{{ model.dialect.name }}</div>
        </div>

        <div v-if="audio" class="pronunciation-audios">
            <AudioItem :model="audio"/>
        </div>
        <div v-else-if="model.audios.length > 0" class="pronunciation-audios">
            <AudioItem v-for="audio in model.audios" :model="audio"/>
        </div>

        <!--                todo: load / toggle -->
        <button v-if="!audio && model.audios_count > 1" @click="fetchAudios">+</button>
    </div>
</template>
