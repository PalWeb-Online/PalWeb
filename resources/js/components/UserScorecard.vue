<script setup>
import {route} from "ziggy-js";
import UserNametag from "./UserNametag.vue";

defineProps({
    user: Object,
    scores: {type: Boolean, default: true}
})
</script>

<template>
    <div class="user-scorecard">
        <Link class="user-avatar" :href="route('users.show', user.username)">
            <img alt="Avatar"
                 :src="user.avatar_url"/>
        </Link>
        <UserNametag :user="user"/>
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

<style scoped lang="scss">
.user-scorecard {
    display: grid;
    gap: 1.6rem;
    font-size: 1.2rem;
    font-weight: 700;
    background: white;
    padding: 0.6rem 0.6rem 1.6rem;
    border-radius: 1.6rem;
    border: 0.2rem solid var(--color-dark-primary);
    filter: drop-shadow(-0.25em 0.25em 0 rgb(0 0 0 / 0.25));
    width: 16em;
    z-index: 2;

    .user-avatar {
        width: 100%;
        height: auto;
        margin: 0;
        aspect-ratio: 1;
        overflow: hidden;
        border-radius: 0;

        img {
            border-radius: 1.2rem;
        }
    }

    .user-name {
        font-weight: 400;
        font-size: 1.6rem;
        padding-inline: 0.8rem;
        color: var(--color-dark-primary);
    }

    .user-scorecard-body {
        padding-inline: 0.6rem;
    }

    .user-creations {
        font-family: var(--mono-font);
        display: flex;
        flex-flow: row wrap;
        gap: 0.8rem;
        align-items: center;
        justify-content: space-between;
        font-size: 1.2rem;
        text-transform: uppercase;
        color: var(--color-medium-primary);

        &:not(:last-child) {
            margin-block-end: 0.4rem;
            padding-block-end: 0.4rem;
            border-block-end: 0.1rem solid var(--color-pastel-medium);
        }
    }
}
</style>
