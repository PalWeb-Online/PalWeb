<script setup>
import {route} from "ziggy-js";

defineProps({
    user: Object,
    speaker: Object,
    size: {type: String, default: 'm'},
    comment: {type: Boolean, default: false},
    tags: {type: Boolean, default: false},
})
</script>

<template>
    <div :class="['user-item', size]">
        <Link class="user-avatar" :href="speaker?.user.private ? '#' : route('users.show', user.username)">
            <img alt="Avatar"
                 :src="`/img/avatars/${ speaker?.user.private ? 'palweb01.jpg' : user.avatar}`"/>
        </Link>

        <div class="user-data-wrapper">
            <div class="user-name">
                <div class="user-name-ar">{{ speaker?.user.private ? 'مجهول' : user.ar_name }}</div>
                <div class="user-name-en">
                    <div>{{ speaker?.user.private ? 'Speaker #' + speaker.id : user.name }}</div>
                    <div>{{ speaker?.user.private ? '[anonymous]' : user.username }}</div>
                </div>
            </div>
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
                    <img class="location" src="/img/location.svg" alt="location"/>
                    <template v-if="user.home">
                        {{ user.home }}
                    </template>
                    <template v-else>
                        Earth, probably.
                    </template>
                </div>
                <div class="user-tag">
                    <img class="dialect" src="/img/mouth.svg" alt="dialect"/>
                    {{ user.dialect.name }}
                </div>
            </div>
            <slot/>
        </div>
    </div>
</template>
