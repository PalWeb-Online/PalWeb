<script setup>
import {route} from "ziggy-js";

defineProps({
    model: Object
})
</script>

<template>
    <div class="comment-item">
        <Link :href="route('users.show', model.user.username)">
            <img alt="Avatar" :src="`/img/avatars/${model.user.avatar}`"/>
        </Link>
        <div class="comment-item-body">
            <div style="display: flex; align-items: center; gap: 0.8rem">
                <div class="comment-item-user">{{ model.user.name }}</div>
                <div class="comment-item-username">· {{ model.user.username }}</div>
            </div>
            <div>{{ model.comment }}</div>
        </div>
    </div>
</template>
