<script setup>
import NavSticky from "./NavSticky.vue";
import NavSidebar from "./NavSidebar.vue";
import SearchGenie from "./SearchGenie.vue";
import Footer from "./Footer.vue";
import AppNotification from "../components/AppNotification.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {useSearchStore} from "../stores/SearchStore.js";
import {usePage} from "@inertiajs/vue3";
import {computed, onMounted, watch} from "vue";
import ModalWrapper from "../components/Modals/ModalWrapper.vue";
import {useUserStore} from "../stores/UserStore.js";
import i18n from "../i18n.js";
import BackgroundPattern from "./Backgrounds/BackgroundPattern.vue";
import {useConnectionStatus} from "../composables/useConnectionStatus.js";
import {useI18n} from "vue-i18n";

const { t } = useI18n();

defineProps({
    section: {
        type: String,
        default: 'library',
    }
});

const UserStore = useUserStore();
const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const page = usePage();
const {browserOnline} = useConnectionStatus(Echo);

const locale = computed(() => page.props.locale ?? 'en');
const syncDocumentLocale = (newLocale) => {
    document.documentElement.lang = newLocale;
};

watch(
    locale,
    (newLocale) => {
        if (newLocale) {
            i18n.global.locale.value = newLocale;
            syncDocumentLocale(newLocale);
        }
    },
    {immediate: true}
);

let lastBrowserOnlineState = browserOnline.value;
let hasSeenInitialConnectionState = false;

onMounted(() => {
    const userId = UserStore.user?.id ?? null;

    if (userId) {
        window.Echo.private(`users.${userId}`)
            .listen('LessonProgressUpdated', (e) => {
                NotificationStore.addNotification(e.message, e.type);
            })
            .listen('UserNotificationSent', (e) => {
                console.log(e);
                NotificationStore.addNotification(e.message, e.type);
            });
    }
});

watch(browserOnline, (isOnline) => {
    if (!hasSeenInitialConnectionState) {
        hasSeenInitialConnectionState = true;
        lastBrowserOnlineState = isOnline;
        return;
    }

    if (isOnline === lastBrowserOnlineState) {
        return;
    }

    lastBrowserOnlineState = isOnline;

    if (!isOnline) {
        NotificationStore.addNotification(
            t('layout.notifications.offline'),
            'warning',
            5000
        );
        return;
    }

    NotificationStore.addNotification(t('layout.notifications.online'), 'success', 3000);
}, {immediate: true});

watch(() => page.props.flash.notification,
    (notification) => {
        if (notification) {
            NotificationStore.addNotification(notification.message, notification.type);
        }
    });

watch(
    () => page.props.locale,
    (newLocale) => {
        if (newLocale) {
            i18n.global.locale.value = newLocale;
        }
    }
);
</script>

<template>
    <NavSticky/>
    <NavSidebar/>

    <div id="app-container" :class="section">
        <slot/>
        <Footer/>

        <div class="app-container-pattern" aria-hidden="true">
            <BackgroundPattern :section="section" />
        </div>
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

<style scoped lang="scss">
#app-container {
    display: grid;
    min-height: calc(100vh - 4.8rem);
    grid-template-rows: min-content 1fr;
    align-content: start;
    position: relative;
    overflow: hidden;

    @media (width >= 960px) {
        min-height: calc(100vh - 3.6rem);
    }

    > *:not(.app-container-pattern) {
        position: relative;
        z-index: 1;
    }

    &.academy {
        background: var(--color-accent-medium);
    }

    &.community, &.office {
        background: var(--color-accent-light);
    }

    &.library {
        background: linear-gradient(90deg, #f6f6f6 calc(2.4rem - 0.3rem), transparent 1%) center / 2.4rem 2.4rem, linear-gradient(#f6f6f6 calc(2.4rem - 0.3rem), transparent 1%) center / 2.4rem 2.4rem, var(--color-pastel-medium);
        background-attachment: fixed;
    }

    &.workbench {
        background: var(--color-medium-primary);
    }

    &.wiki, &.account {
        background: var(--color-pastel-light);
    }

    &.account {
        @media (width <= 960px) {
            #app-body > .app-tip {
                border-radius: 0;
                border: none;
                box-shadow: 0 0.2rem 0 rgb(0 0 0 / 0.1);
            }
        }
    }
}
</style>
