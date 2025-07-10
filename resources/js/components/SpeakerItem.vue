<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    speaker: Object,
});
</script>

<template>
    <div class="speaker-data">
        <div class="speaker-data-head">
            <div>Speaker Data</div>
            <Link v-if="$page.component === 'Community/Users/Show'" :href="route('speaker.show', speaker.id)"
                  class="material-symbols-rounded">open_in_new</Link>
            <Link v-else-if="speaker.user.id === UserStore.user.id" :href="route('record-wizard.index')"
                  class="material-symbols-rounded">edit</Link>
        </div>
        <div class="speaker-data-row">
            <div>Dialect</div>
            <div>{{ speaker.dialect.name }}</div>
        </div>
        <div class="speaker-data-row">
            <div>Location</div>
            <div>{{ speaker.location.name_ar }} ({{ speaker.location.name_en }})</div>
        </div>
        <div class="speaker-data-row">
            <div>Fluency</div>
            <div>{{ speaker.fluency_alias }}</div>
        </div>
        <div class="speaker-data-row">
            <div>Gender</div>
            <div>{{ speaker.gender }}</div>
        </div>
        <div class="speaker-data-row">
            <div>Audios</div>
            <div>{{ speaker.audios_count }}</div>
        </div>
    </div>
</template>
