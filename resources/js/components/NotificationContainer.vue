<script setup>
import {computed, onMounted, ref} from 'vue';
import { useNotificationStore } from '../stores/NotificationStore';
import AppNotification from './AppNotification.vue';

const NotificationStore = useNotificationStore();
const notifications = computed(() => NotificationStore.notifications);
const isVisible = computed(() => notifications.value.length > 0);
</script>

<template>
    <div class="notification-container" :class="{'visible': isVisible}">
        <AppNotification
            v-for="notification in notifications"
            :key="notification.id"
            :message="notification.message"
            :type="notification.type"
        />
    </div>
</template>
