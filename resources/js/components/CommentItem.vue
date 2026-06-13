<script setup>
import UserNametag from "./UserNametag.vue";
import UserAvatarWrapper from "./UserAvatarWrapper.vue";
import {route} from "ziggy-js";
import {computed} from "vue";

const props = defineProps({
    user: Object,
    model: Object,
})

const resolvedUser = computed(() => props.user ?? props.model.user);
</script>

<template>
    <div class="user-item comment-item">
        <UserAvatarWrapper :user="resolvedUser">
            <Link class="material-symbols-rounded" :href="route('users.show', resolvedUser.username)">
                account_circle
            </Link>

            <a v-if="resolvedUser.teacher?.email" class="material-symbols-rounded" :href="`mailto:${resolvedUser.teacher?.email}`">mail</a>
        </UserAvatarWrapper>
        <div class="user-data-wrapper">
            <div class="user-comment">
                <UserNametag :user="resolvedUser"/>
                <slot>
                    <div class="user-comment-content">
                        {{ model.comment }}
                    </div>
                    <div v-if="model.created_at" class="user-comment-data">
                        on {{ model.created_at }}.
                    </div>
                </slot>
            </div>
        </div>
    </div>
</template>
