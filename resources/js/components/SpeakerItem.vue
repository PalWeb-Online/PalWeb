<script setup>
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    speaker: Object,
});

const localeKey = (value) => value?.toString().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
</script>

<template>
    <div class="speaker-data">
        <div class="speaker-data-head">
            <div>{{ $t('speaker.data-title') }}</div>
            <RouterLink v-if="$page.component === 'Community/Users/Show'" :to="{ name: 'speaker.show', params: { speaker: speaker.id } }"
                  class="material-symbols-rounded">open_in_new</RouterLink>
            <RouterLink v-else-if="speaker.user.id === UserStore.user.id" :to="{ name: 'sound-booth.index' }"
                  class="material-symbols-rounded">edit</RouterLink>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('speaker.fields.dialect') }}</div>
            <div>{{ $t(`dialect.${localeKey(speaker.dialect.name)}`) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('speaker.fields.location') }}</div>
            <div>{{ $t('speaker.location', {ar: speaker.location.name_ar, en: speaker.location.name_en}) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('speaker.fields.fluency') }}</div>
            <div>{{ $t(`speaker.fluency.${speaker.fluency}`) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('speaker.fields.gender') }}</div>
            <div>{{ $t(`speaker.gender.${speaker.gender}`) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('speaker.audios') }}</div>
            <div>{{ speaker.audios_count }}</div>
        </div>
    </div>
</template>
