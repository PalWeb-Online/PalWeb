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

<style scoped lang="scss">
.user-scorecard {
    display: grid;
    gap: 0.8rem;
    background: white;
    font-size: 1.2rem;
    font-weight: 700;
    padding: 0.6rem 0.6rem 1.2rem;
    border-radius: 1.6rem;
    border: 0.2rem solid var(--color-dark-primary);
    filter: drop-shadow(-0.25em 0.25em 0 rgb(0 0 0 / 0.25));
    z-index: 2;

    .user-avatar {
        width: 16em;
        height: 16em;
        border-radius: 1.2rem;
    }

    .user-name {
        justify-self: center;
        font-size: 2.4rem;
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
