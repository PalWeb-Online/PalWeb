<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import {router} from "@inertiajs/vue3";

const UserStore = useUserStore();
</script>

<template>
    <template v-if="UserStore.user">
        <div class="nav-auth-wrapper">
            <div class="auth-role">
                {{ UserStore.highestRole }}
            </div>
            <Link class="nav-auth" :href="route('users.show', UserStore.user.username)">
                <div class="user-avatar">
                    <img alt="User Avatar"
                         :src="`/img/avatars/${UserStore.user.avatar}`"/>
                </div>

                <div class="user-name">
                    <div class="user-name-ar">{{ UserStore.user.ar_name }}</div>
                    <div class="user-name-en">
                        <div>{{ UserStore.user.name }}</div>
                        <div>{{ UserStore.user.username }}</div>
                    </div>
                </div>
            </Link>
            <div class="auth-email">
                <div>{{ UserStore.user.email }}</div>
                <div v-if="UserStore.user.is_verified">Verified</div>
                <div v-else>Unverified
                    <span v-if="!UserStore.user.is_verified" @click="router.post(route('verification.send'))">Resend Link</span>
                </div>
            </div>
        </div>
    </template>
</template>
