<script setup>
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3';

const props = defineProps({
    model: Object,
});
</script>

<template>
    <div class="speaker-profile-container">
        <div class="speaker-profile-head">
            <div>Speaker</div>

            <!--    todo: if page is not Users/Show-->
            <div v-if="model.user.private" style="padding-inline: 1.6rem">Speaker #{{ model.id }}</div>
            <Link v-else class="mini-user-profile" :href="route('users.show', model.user)">
                <div>{{ model.user.name }} ({{ model.user.username }})</div>
                <img alt="User Avatar" :src="`/img/avatars/${model.user.avatar}`"/>
            </Link>
        </div>

        <div class="speaker-profile-body">
            <div class="speaker-profile-row">
                <div>Dialect</div>
                <div>{{ model.dialect.name }}</div>
            </div>
            <div class="speaker-profile-row">
                <div>Location</div>
                <div>{{ model.location.name_ar }} ({{ model.location.name_en }})</div>
            </div>
            <div class="speaker-profile-row">
                <div>Gender</div>
                <div>{{ model.gender }}</div>
            </div>
            <div class="speaker-profile-row">
                <div>Fluency</div>
                <div>{{ model.fluency_alias }}</div>
            </div>
            <div class="speaker-profile-row">
                <div>Audios</div>
                <div>{{ model.audios_count }}</div>
            </div>
        </div>

        <!--    todo: if page is Users/Show-->
    <!--        @if(!request()->routeIs('speaker.show') && $speaker->audios->count() > 0)-->
    <!--        <a href="{{ route('speaker.show', $speaker) }}">See All Audios by this Speaker</a>-->
    <!--            -->
    <!--        <div class="featured-title s">Latest</div>-->
    <!--        @foreach($speaker->audios->sortByDesc('id')->take(5) as $audio)-->
    <!--        <x-pronunciation-item :pronunciation="$audio->pronunciation" :audio="$audio"/>-->
    <!--        @endforeach-->
    <!--        @endif-->
    </div>
</template>
