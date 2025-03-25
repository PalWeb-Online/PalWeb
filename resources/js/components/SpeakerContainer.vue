<script setup>
import PronunciationItem from "./PronunciationItem.vue";
import {route} from 'ziggy-js';

const props = defineProps({
    speaker: Object,
});
</script>

<template>
    <div class="speaker-profile-container">
        <div class="speaker-profile-head">
            <div>Speaker</div>

            <template v-if="$page.component === 'Library/Audios/Speaker'">
                <div v-if="speaker.user.private" style="padding-inline: 1.6rem">Speaker #{{ speaker.id }}</div>
                <Link v-else class="mini-user-profile" :href="route('users.show', speaker.user)">
                    <div>{{ speaker.user.name }} ({{ speaker.user.username }})</div>
                    <img alt="User Avatar" :src="`/img/avatars/${speaker.user.avatar}`"/>
                </Link>
            </template>
        </div>

        <div class="speaker-profile-body">
            <div class="speaker-profile-row">
                <div>Dialect</div>
                <div>{{ speaker.dialect.name }}</div>
            </div>
            <div class="speaker-profile-row">
                <div>Location</div>
                <div>{{ speaker.location.name_ar }} ({{ speaker.location.name_en }})</div>
            </div>
            <div class="speaker-profile-row">
                <div>Gender</div>
                <div>{{ speaker.gender }}</div>
            </div>
            <div class="speaker-profile-row">
                <div>Fluency</div>
                <div>{{ speaker.fluency_alias }}</div>
            </div>
            <div class="speaker-profile-row">
                <div>Audios</div>
                <div>{{ speaker.audios_count }}</div>
            </div>
        </div>

        <template v-if="$page.component === 'Community/Users/Show' && speaker.audios_count > 0">
            <Link :href="route('speaker.show', speaker.id)">See All Audios by this Speaker</Link>

            <div class="featured-title s">Latest</div>
            <PronunciationItem v-for="audio in speaker.audios" :model="audio.pronunciation" :audio="audio"/>
        </template>
    </div>
</template>
