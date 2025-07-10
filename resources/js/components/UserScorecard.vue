<script setup>
import {route} from "ziggy-js";

defineProps({
    user: Object,
    scores: {type: Boolean, default: true}
})
</script>

<template>
    <div class="user-scorecard">
        <Link class="user-avatar" :href="route('users.show', user.username)">
            <img alt="Avatar"
                 :src="`/img/avatars/${user.avatar}`"/>
        </Link>
        <div class="user-name">
            <div class="user-name-ar">{{ user.ar_name }}</div>
        </div>
        <div v-if="scores" class="user-scorecard-body">
            <div class="user-creations">
                <div>decks</div>
                <div>{{ user.decks_count }}</div>
            </div>
            <div class="user-creations">
                <div>audios</div>
                <div>{{ user.audios_count }}</div>
            </div>
        </div>
    </div>
</template>
