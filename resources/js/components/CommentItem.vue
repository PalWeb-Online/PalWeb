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

<style scoped lang="scss">
.comment-item {
    display: flex;
    align-items: flex-start;
    gap: 1.6rem;

    img {
        width: 9.6rem;
        border-radius: 50%;
    }

    .comment-item-body {
        margin-block-start: 1.6rem;
        width: min(100%, 36rem);
        display: grid;
        gap: 0.8rem;
        background: var(--color-pastel-light);
        border-radius: 0.4rem 1.6rem 1.6rem 1.6rem;
        padding: 2.4rem;
        box-shadow: -0.4rem 0.4rem 0 rgb(0 0 0 / 0.125);
    }

    .comment-item-user {
        font-family: var(--head-font);
        font-weight: 700;
        font-size: 1.6rem;
        color: var(--color-dark-primary);
    }

    .comment-item-username {
        font-family: var(--mono-font);
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--color-dark-primary);
    }
}
</style>
