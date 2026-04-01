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
            <Link v-if="audio" class="pronunciation-item-term"
                  :href="route('terms.show', model.term.slug)">{{ model.term.term }}
            </Link>
            <div class="pronunciation-item-data">
                <span class="pronunciation-item-dialect">{{ model.dialect.name }}</span>
                <span class="pronunciation-item-phonology">{{
                        model.borrowed === true ? '(Borrowed)' : ''
                    }} {{ model.translit }}</span>
                <span class="pronunciation-item-phonology">{{ model.phonemic }}</span>
                <span class="pronunciation-item-phonology">{{ model.phonetic }}</span>
            </div>
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

<style scoped lang="scss">
.pronunciation-item-container {
    display: grid;

    .pronunciation-audios {
        flex-shrink: 0;
        display: grid;
        gap: 0.1rem;
        justify-items: start;
        border-inline-start: none;
        background: var(--color-pastel-dark);
        border-block-start: 0.1rem solid var(--color-dark-primary);

        & > * {
            min-width: 0;
        }
    }

    & > button {
        text-align: center;
        background: var(--color-pastel-medium);
        padding-block-end: 0.2rem;

        &:hover {
            text-decoration: none;
            background: var(--color-pastel-dark);
        }
    }

    &.inline {
        display: flex;

        .pronunciation-item {
            flex-grow: 1;
        }

        .pronunciation-audios {
            border-block-start: none;
        }
    }
}

.pronunciation-item {
    display: flex;
    flex-flow: row-reverse wrap;
    background: var(--color-accent-light);

    .pronunciation-item-term,
    .pronunciation-item-data {
        padding-inline: 1.2rem;
        min-height: 3.6rem;
    }

    .pronunciation-item-term {
        flex-basis: 20%;
        padding-block-start: 0.1rem;
        font-family: var(--ar-body-font), serif;
        font-size: 1.8rem;
        font-weight: 700;
        white-space: nowrap;
        display: flex;
        align-items: center;
        direction: rtl;

        &:hover {
            color: var(--color-medium-primary);
        }
    }

    .pronunciation-item-data {
        padding-block: 0.8rem;
        flex-grow: 1;
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        column-gap: 0.8rem;
        white-space: nowrap;
        background: var(--color-polar-light);
    }

    .pronunciation-item-dialect {
        padding-block-end: 0.1rem;
        margin-inline-end: 0.8rem;
        font-family: var(--body-font), serif;
        font-weight: 700;
        font-size: 1.4rem;
    }

    .pronunciation-item-phonology {
        font-family: var(--mono-font), monospace;
        font-size: 1.2rem;
    }
}
</style>
