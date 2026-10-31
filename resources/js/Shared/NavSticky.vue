<script setup>
import {useNavigationStore} from '../stores/NavigationStore.js';
import {useSearchStore} from '../stores/SearchStore.js';
import {computed, onBeforeUnmount, onMounted, ref} from 'vue';
import GlobalSettings from '../components/Modals/GlobalSettings.vue';
import ModalWrapper from '../components/Modals/ModalWrapper.vue';
import InstallButton from './InstallButton.vue';
import {useNotificationStore} from '../stores/NotificationStore.js';
import AppHint from '../components/AppHint.vue';

const NavigationStore = useNavigationStore();
const NotificationStore = useNotificationStore();
const SearchStore = useSearchStore();

const showGlobalSettings = ref(false);

const timeZone = 'Asia/Jerusalem';
const displayedTime = ref(Date.now());

const updateDisplayedTime = () => {
    displayedTime.value = Date.now();
};

const formattedTime = computed(() =>
    new Intl.DateTimeFormat('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
        timeZone,
    }).format(displayedTime.value),
);

const utcOffsetHours = computed(() => {
    const parts = new Intl.DateTimeFormat('en-US', {
        timeZone,
        timeZoneName: 'longOffset',
    }).formatToParts(displayedTime.value);

    const offset = parts.find(part => part.type === 'timeZoneName')?.value;
    const match = offset?.match(/GMT([+-])(\d{2}):(\d{2})/);

    if (!match) return 0;

    const [, sign, hours, minutes] = match;
    const value = Number(hours) + Number(minutes) / 60;

    return sign === '+' ? value : -value;
});

let intervalId;

const globalListener = (event) => {
    const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;

    if (
        (isMac && event.metaKey && event.key === 'k') ||
        (!isMac && event.ctrlKey && event.key === 'k')
    ) {
        event.preventDefault();

        if (
            SearchStore.data.isOpen &&
            SearchStore.data.action === 'search'
        ) {
            SearchStore.data.isOpen = false;
        } else if (!SearchStore.data.isOpen) {
            SearchStore.openSearchGenie('search');
        }
    }

    if (
        ((isMac && event.metaKey) || (!isMac && event.ctrlKey)) &&
        event.key === 'm'
    ) {
        event.preventDefault();
        NavigationStore.toggleSidebar();
    }
};

const visibilityListener = () => {
    if (document.visibilityState === 'visible') {
        updateDisplayedTime();
    }
};

onMounted(() => {
    intervalId = window.setInterval(updateDisplayedTime, 1000);
    document.addEventListener('visibilitychange', visibilityListener);
    window.addEventListener('keydown', globalListener);
});

onBeforeUnmount(() => {
    window.clearInterval(intervalId);
    document.removeEventListener('visibilitychange', visibilityListener);
    window.removeEventListener('keydown', globalListener);
});
</script>
<template>
    <div class="nav-sticky">
        <div class="nav-sticky-info">
            <div>JER {{ formattedTime }} (UTC +{{ utcOffsetHours }})</div>
            <InstallButton/>
            <RouterLink :to="{ name: 'homepage' }">PalWeb 2.4 (Msaxxan)</RouterLink>
        </div>
        <div class="nav-sticky-buttons">
            <button
                class="material-symbols-rounded"
                @click="SearchStore.openSearchGenie('search')"
                type="button"
                :aria-label="$t('nav.sticky.search')"
            >
                search
            </button>

            <AppHint
                v-model="NotificationStore.showPushSubscribeHint"
                :message="$t('nav.sticky.push-subscribe-hint')"
            >
                <template #default="{ setReference }">
                    <button
                        :ref="setReference"
                        class="material-symbols-rounded"
                        @click="showGlobalSettings = true"
                        type="button"
                        :aria-label="$t('nav.sticky.settings')"
                    >
                        settings
                    </button>
                </template>
            </AppHint>
            <button
                class="material-symbols-rounded"
                @click.stop="NavigationStore.toggleSidebar"
                type="button"
                :aria-label="$t('nav.sticky.menu')"
            >
                menu
            </button>
        </div>
    </div>
    <ModalWrapper v-model="showGlobalSettings">
        <GlobalSettings/>
    </ModalWrapper>
</template>

<style scoped lang="scss">
.nav-sticky {
    position: sticky;
    top: 0;
    display: flex;
    justify-content: space-between;
    color: white;
    background: var(--color-medium-secondary);
    width: 100%;
    height: 3em;
    font-size: 1.6rem;
    z-index: 1;
    user-select: none;

    @media (width >= 960px) {
        font-size: 1.2rem;
    }
}

.nav-sticky-info {
    display: flex;
    font-family: var(--mono-font), monospace;

    & > * {
        align-items: center;
    }

    & > *:nth-child(1) {
        display: none;
        background: var(--color-dark-secondary);
        padding-inline: 1.6rem;

        @media (width >= 960px) {
            display: flex;
        }
    }

    .material-symbols-rounded {
        display: flex;
        justify-content: center;
        color: var(--color-medium-primary);
        background: var(--color-dark-primary);
        font-size: 2.4rem;
        width: 2em;
        flex-shrink: 0;

        @media (width >= 960px) {
            font-size: 1.8rem;
        }
    }

    a {
        display: flex;
        padding-inline: 1.6rem;
    }

    a:hover {
        text-decoration: 1px solid underline;
    }
}
</style>
