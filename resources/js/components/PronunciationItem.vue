<script setup>
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3';
import AudioItem from "./AudioItem.vue";

const props = defineProps({
    id: Number,
    model: {
        type: Object,
        required: false,
        default: null,
    },
    audio: Object,
});
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

        <div class="pronunciation-audios">
            <AudioItem v-if="audio" :model="audio"/>
            <AudioItem v-else-if="model.audios.length > 0" v-for="audio in model.audios" :model="audio"/>
        </div>

<!--        todo: on Term Page -->
        <!--            @if(!request()->routeIs('terms.audios') && $pronunciation->audio_count > 1)-->
        <!--            <a href="{{ route('terms.audios', $pronunciation->term) }}">+</a>-->
        <!--            @endif-->
    </div>
</template>
