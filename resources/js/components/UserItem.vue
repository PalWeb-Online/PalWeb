<script setup>
import {route} from "ziggy-js";

defineProps({
    user: Object,
    size: {type: String, default: 'm'},
    comment: {type: Boolean, default: false},
    tags: {type: Boolean, default: false},
})
</script>

<template>
    <div :class="['user-item', size]">
        <Link class="user-avatar" :href="route('users.show', user.username)">
            <img alt="Avatar"
                 :src="`/img/avatars/${user.avatar}`"/>
        </Link>
        <div class="user-scorecard" v-if="size === 's'">
            <div>{{ user.ar_name }}</div>
            <div class="user-creations">
                <div>decks</div>
                <div>{{ user.decks_count }}</div>
            </div>
            <div class="user-creations">
                <div>audios</div>
                <div>{{ user.audios_count }}</div>
            </div>
        </div>

        <div class="user-data-wrapper">
            <div class="user-name">
                <div class="user-name-ar">{{ user.ar_name }}</div>
                <div class="user-name-en">
                    <div>{{ user.name }}</div>
                    <div>{{ user.username }}</div>
                </div>
            </div>
            <div v-if="comment" class="user-comment">
                <slot>
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
        </div>
    </div>
</template>
