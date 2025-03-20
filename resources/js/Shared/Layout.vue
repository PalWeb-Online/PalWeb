<script setup>
import Nav from "./Nav.vue";
import NotificationContainer from "../components/NotificationContainer.vue";
import SearchGenie from "./SearchGenie.vue";
import SearchGenieTrigger from "./SearchGenieTrigger.vue";
import AppNotification from "../components/AppNotification.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {usePage} from "@inertiajs/vue3";
import {watch} from "vue";

defineProps({
    section: {
        type: String,
        default: 'library',
    }
});
const NotificationStore = useNotificationStore();

const page = usePage();

watch(() => page.props.flash.notification, (notification) => {
    if (notification) {
        NotificationStore.addNotification(notification.message, notification.type);
    }
});
</script>

<template>
    <div id="app-container" :class="section">
        <Nav/>

        <slot/>

        <SearchGenieTrigger/>
        <SearchGenie/>
        <NotificationContainer/>

    <div class="notification-container" :class="{'visible': NotificationStore.notifications.length > 0}">
        <AppNotification
            v-for="notification in NotificationStore.notifications"
            :key="notification.id"
            :message="notification.message"
            :type="notification.type"
        />
    </div>
</template>
