<script setup>
import NavSticky from "./NavSticky.vue";
import NavSidebar from "./NavSidebar.vue";
import SearchGenie from "./SearchGenie.vue";
import Footer from "./Footer.vue";
import AppNotification from "../components/AppNotification.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {useSearchStore} from "../stores/SearchStore.js";
import {usePage} from "@inertiajs/vue3";
import {watch} from "vue";
import ModalWrapper from "../components/Modals/ModalWrapper.vue";

defineProps({
    section: {
        type: String,
        default: 'library',
    }
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const page = usePage();

watch(() => page.props.flash.notification, (notification) => {
    if (notification) {
        NotificationStore.addNotification(notification.message, notification.type);
    }
});
</script>

<template>
    <NavSticky/>
    <NavSidebar/>

    <div id="app-container" :class="section">
        <slot/>

        <Footer v-if="![
            'Workbench/RecordWizard/RecordWizard',
            'Workbench/DeckMaster/DeckMaster',
            'Workbench/SpeechMaker/SpeechMaker',
            ].includes($page.component)"/>
    </div>

    <ModalWrapper v-model="SearchStore.data.isOpen">
        <SearchGenie @close="SearchStore.data.isOpen = false"/>
    </ModalWrapper>

    <div class="notification-container" :class="{'visible': NotificationStore.notifications.length > 0}">
        <AppNotification
            v-for="notification in NotificationStore.notifications"
            :key="notification.id"
            :message="notification.message"
            :type="notification.type"
        />
    </div>
</template>
