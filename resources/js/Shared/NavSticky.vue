<script setup>
import { route } from "ziggy-js";
import { useNavigationStore } from "../stores/NavigationStore.js";
import { useSearchStore } from "../stores/SearchStore.js";
import { onBeforeUnmount, onMounted, ref } from "vue";
import ThemePicker from "../components/Modals/ThemePicker.vue";
import { usePage } from "@inertiajs/vue3";
import ModalWrapper from "../components/Modals/ModalWrapper.vue";

const NavigationStore = useNavigationStore();
const SearchStore = useSearchStore();

const showThemePicker = ref(false);

const {
    props: { utcOffsetMinutes },
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

            // animation loop isn't running, start it up again
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
            <Link :href="route('homepage')">PalWeb 2.0 (Watermelon)</Link>
        </div>
        <div class="nav-sticky-buttons">
            <button
                class="material-symbols-rounded"
                @click="showThemePicker = true"
            >
                palette
            </button>
            <button
                class="material-symbols-rounded"
                @click="SearchStore.openSearchGenie('search')"
            >
                search
            </button>
            <button
                class="material-symbols-rounded"
                @click.stop="NavigationStore.toggleSidebar"
            >
                menu
            </button>
        </div>
    </div>

    <!--    <x-dropdown>-->
    <!--        <x-slot name="trigger">-->
    <!--            <a class="material-symbols-rounded" @click="open = ! open">language</a>-->
    <!--        </x-slot>-->
    <!--        <x-slot name="content">-->
    <!--            <form id="frm-en" method="post" action="{{ route("language.store", "en") }}">-->
    <!--            @csrf-->
    <!--            <button onclick="this.closest('form').submit();return false;">EN</button>-->
    <!--            </form>-->

    <!--            <form id="frm-es" method="post" action="{{ route("language.store", "es") }}">-->
    <!--            @csrf-->
    <!--            <button onclick="this.closest('form').submit();return false;">ES</button>-->
    <!--            </form>-->

    <!--            <form id="frm-ar" method="post" action="{{ route("language.store", "ar") }}">-->
    <!--            @csrf-->
    <!--            <button onclick="this.closest('form').submit();return false;">AR</button>-->
    <!--            </form>-->
    <!--        </x-slot>-->
    <!--    </x-dropdown>-->

    <ModalWrapper v-model="showThemePicker">
        <ThemePicker />
    </ModalWrapper>
</template>
