<script setup>
import {route} from "ziggy-js";
import {useNavigationStore} from "../stores/NavigationStore.js";
import {useSearchStore} from "../stores/SearchStore.js";
import {onBeforeUnmount, onMounted, ref} from "vue";
import GlobalSettings from "../components/Modals/GlobalSettings.vue";
import {usePage} from "@inertiajs/vue3";
import ModalWrapper from "../components/Modals/ModalWrapper.vue";
import InstallButton from "./InstallButton.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";
import AppHint from "../components/AppHint.vue";

const NavigationStore = useNavigationStore();
const NotificationStore = useNotificationStore();
const SearchStore = useSearchStore();

const showGlobalSettings = ref(false);

const {
    props: {utcOffsetMinutes},
} = usePage();

const utcOffsetHours = utcOffsetMinutes / 60;

const displayedTime = ref(Date.now());

let animationFrameId = null;
let previousAnimationTime = 0;

const updateDisplayedTime = (currentAnimationTime) => {
    const elapsed = currentAnimationTime - previousAnimationTime;

    if (elapsed >= 1000) {
        displayedTime.value = Date.now();

        previousAnimationTime = currentAnimationTime;
    }

    animationFrameId = requestAnimationFrame(updateDisplayedTime);
};

onMounted(() => {
    animationFrameId = requestAnimationFrame(updateDisplayedTime);

    const globalListener = (event) => {
        const isMac = navigator.platform.toUpperCase().indexOf("MAC") >= 0;

        if (
            (isMac && event.metaKey && event.key === "k") ||
            (!isMac && event.ctrlKey && event.key === "k")
        ) {
            event.preventDefault();

            if (
                SearchStore.data.isOpen &&
                SearchStore.data.action === "search"
            ) {
                SearchStore.data.isOpen = false;
            } else if (!SearchStore.data.isOpen) {
                SearchStore.openSearchGenie("search");
            }
        }

        if (
            ((isMac && event.metaKey) || (!isMac && event.ctrlKey)) &&
            event.key === "m"
        ) {
            event.preventDefault();
            NavigationStore.toggleSidebar();
        }
    };

    const handleVisibilityChange = () => {
        if (document.visibilityState === "visible") {
            displayedTime.value = Date.now();

            if (animationFrameId === null) {
                animationFrameId = requestAnimationFrame(updateDisplayedTime);
            }
        } else {
            cancelAnimationFrame(animationFrameId);
            animationFrameId = null;
        }
    };

    window.addEventListener("keydown", globalListener);
    document.addEventListener("visibilitychange", handleVisibilityChange);

    onBeforeUnmount(() => {
        window.removeEventListener("keydown", globalListener);
        document.removeEventListener(
            "visibilitychange",
            handleVisibilityChange,
        );
    });
});
</script>
<template>
    <div class="nav-sticky">
        <div class="nav-sticky-info">
            <div>
                JER
                {{
                    new Date(displayedTime).toLocaleTimeString("en-US", {
                        timeZone: "Asia/Jerusalem",
                        hour12: false,
                    })
                }}
                (UTC +{{ utcOffsetHours }})
            </div>
            <InstallButton/>
            <Link :href="route('homepage')">PalWeb 2.3 (Falastin)</Link>
        </div>
        <div class="nav-sticky-buttons">
            <button
                class="material-symbols-rounded"
                @click="SearchStore.openSearchGenie('search')"
                type="button"
                aria-label="Search"
            >
                search
            </button>

            <AppHint
                v-model="NotificationStore.showPushSubscribeHint"
                message="Enable Notifications in Settings to get a daily reminder to review your Cards!"
            >
                <template #default="{ setReference }">
                    <button
                        :ref="setReference"
                        class="material-symbols-rounded"
                        @click="showGlobalSettings = true"
                        type="button"
                        aria-label="Settings"
                    >
                        settings
                    </button>
                </template>
            </AppHint>
            <button
                class="material-symbols-rounded"
                @click.stop="NavigationStore.toggleSidebar"
                type="button"
                aria-label="Menu"
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
    z-index: 999;
    user-select: none;

    @media (min-width: 960px) {
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
        background: var(--color-dark-secondary);
        padding-inline: 1.6rem;

        display: none;

        @media (min-width: 960px) {
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

        @media (min-width: 960px) {
            font-size: 1.8rem;
        }
    }

    a {
        display: flex;
        padding-inline: 1.6rem;
    }

    a:hover {
        text-decoration: 0.1rem solid underline;
    }
}
</style>
