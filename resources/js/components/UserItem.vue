<script setup>
import UserNametag from "./UserNametag.vue";
import UserAvatarWrapper from "./UserAvatarWrapper.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";

const props = defineProps({
    user: Object,
    speaker: Object,
    size: {type: String, default: 'm'},
    comment: {type: Boolean, default: false},
    tags: {type: Boolean, default: false},
})

const NotificationStore = useNotificationStore();

const copyText = (event) => {
    event.preventDefault();

    navigator.clipboard.writeText(props.user.teacher?.email).then(function () {
        NotificationStore.addNotification('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
};
</script>

<template>
    <div :class="['user-item', size]">
        <UserAvatarWrapper :user="!speaker?.user.private ? user : null">
            <a v-if="user.teacher" class="material-symbols-rounded" :href="`mailto:${user.teacher.email}`">mail</a>
        </UserAvatarWrapper>
        <div class="user-data-wrapper">
            <UserNametag :user="user" :speaker="speaker"/>
            <div v-if="comment" class="user-comment">
                <slot name="comment">
                    <div class="user-comment-content">
                        <template v-if="user.bio">
                            {{ user.bio }}
                        </template>
                        <template v-else>
                            <i>Sadly, {{ user.name }} hasn't told us anything about themselves yet.</i>
                        </template>
                    </div>
                    <div class="user-comment-data">
                        Joined on {{ user.created_at }} ({{ user.created_ago }}).
                    </div>
                </slot>
            </div>
            <div v-if="tags" class="user-tag-wrapper">
                <div class="user-tag">
                    <div class="material-symbols-rounded">location_on</div>
                    <span v-if="user.home">{{ user.home }}</span>
                    <span v-else>Earth, probably.</span>
                </div>
                <div class="user-tag">
                    <div class="material-symbols-rounded">lips</div>
                    <span>{{ user.dialect.name }}</span>
                </div>
                <div class="user-tag" v-if="user.teacher">
                    <div class="material-symbols-rounded">mail</div>
                    <span @click="copyText">{{ user.teacher.email }}</span>
                </div>
            </div>
            <slot/>
        </div>
    </div>
</template>
