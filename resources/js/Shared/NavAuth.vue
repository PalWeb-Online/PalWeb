<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import {router} from "@inertiajs/vue3";

const UserStore = useUserStore();
</script>

<template>
    <div class="auth-user">
        <Link class="user-avatar" :href="route('users.show', UserStore.user.username)">
            <img alt="User Avatar"
                 :src="`/img/avatars/${UserStore.user.avatar}`"/>
        </Link>

        <div class="user-name">
            <div class="user-name-ar">{{ UserStore.user.ar_name }}</div>
            <div class="user-name-en">
                <div>{{ UserStore.user.name }}</div>
                <div>{{ UserStore.user.username }}</div>
            </div>
        </div>
    </div>
    <div class="auth-email">
        <div>{{ UserStore.user.email }}</div>
        <span v-if="UserStore.user.is_verified" class="material-symbols-rounded">
                verified
            </span>
        <template v-else>
            <span class="material-symbols-rounded unverified">
                verified_off
            </span>
            <span class="resend-prompt"
                  @click="router.post(route('verification.send'))">
                Resend Link
            </span>
        </template>
    </div>
</template>

<style scoped lang="scss">
.auth-user {
    display: grid;
    grid-template-columns: 9.6rem 1fr;
    background: var(--color-pastel-light);

    .user-avatar {
        font-size: 0.6rem;
        border-radius: 0 0 1.6rem 0;
    }

    .user-name {
        background: white;
        padding: 0.4rem 1.2rem 0.8rem;
        border-radius: 0 0 0 1.6rem;
        z-index: 1;

        .user-name-ar {
            font-size: 3.2rem;
        }

        .user-name-en {
            flex-basis: 100%;

            & > *:nth-child(1) {
                font-size: 1.8rem;
            }

            & > *:nth-child(2) {
                font-size: 1.2rem;
            }
        }
    }
}

.auth-email {
    background: var(--color-pastel-light);
    border-block-end: 0.8rem solid var(--color-pastel-medium);
    padding: 0.8rem 1.2rem;
    display: flex;
    flex-flow: row wrap;
    align-items: center;
    gap: 0.8rem;
    font-weight: 700;
    color: var(--color-dark-primary);

    & > *:nth-child(1) {
        font-family: var(--mono-font);
        font-size: 1.4rem;
    }

    .material-symbols-rounded {
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--color-medium-primary);

        &.unverified {
            color: var(--color-accent-medium);
        }
    }

    .resend-prompt {
        display: flex;
        justify-content: space-between;
        font-family: var(--mono-font);
        font-size: 1.4rem;
        color: var(--color-medium-primary);
        cursor: pointer;

        &:hover {
            text-decoration: 0.2rem solid underline;
        }
    }
}
</style>
