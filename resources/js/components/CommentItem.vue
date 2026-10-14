<script setup>
import UserNametag from "./UserNametag.vue";
import UserAvatarWrapper from "./UserAvatarWrapper.vue";
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
            <RouterLink class="material-symbols-rounded" :to="{ name: 'users.show', params: { user: resolvedUser.username } }">
                account_circle
            </RouterLink>

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
                        {{ $t('components.comment.created-on', {date: model.created_at}) }}
                    </div>
                </slot>
            </div>
        </div>
    </div>
</template>
