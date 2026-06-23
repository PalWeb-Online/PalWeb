<script setup>
import {route} from "ziggy-js";
import {useSlots} from "vue";
import UserAvatarFrame from "./UserAvatarFrame.vue";

defineProps({
    user: Object,
})

const slots = useSlots()
</script>

<template>
    <div class="user-avatar-wrapper">
        <Link class="user-avatar" :href="user ? route('users.show', user.username) : '#'">
            <UserAvatarFrame/>
            <img alt="Avatar" :src="user?.avatar_url ?? '/img/avatars/palweb01.webp'"/>
        </Link>
        <div v-if="slots.default" class="user-interact-buttons">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">
.user-avatar-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5em;

    @media (width >= 720px) {
        display: grid;
    }

    .user-interact-buttons {
        display: flex;
        gap: 0.5em;
        justify-content: center;
    }
}
</style>
