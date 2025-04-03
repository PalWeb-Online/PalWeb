<script setup>
import {route} from "ziggy-js";
import {useNavigationStore} from "../stores/NavigationStore.js";
import {useSearchStore} from "../stores/SearchStore.js";
import {onBeforeUnmount, onMounted, ref} from "vue";
import ThemePicker from "../components/Modals/ThemePicker.vue";
import {usePage} from "@inertiajs/vue3";
import ModalWrapper from "../components/Modals/ModalWrapper.vue";

const NavigationStore = useNavigationStore();
const SearchStore = useSearchStore();

const showThemePicker = ref(false);

const page = usePage();
const time = ref(new Date(page.props.time));

const updateTime = () => {
    time.value = new Date(time.value.getTime() + 1000);
};

onMounted(() => {
    setInterval(updateTime, 1000);

    const globalListener = (event) => {
        const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;

        if ((isMac && event.metaKey && event.key === 'k') || (!isMac && event.ctrlKey && event.key === 'k')) {
            event.preventDefault();
            if (SearchStore.data.isOpen && SearchStore.data.action === 'search') {
                SearchStore.data.isOpen = false;

            } else if (!SearchStore.data.isOpen) {
                SearchStore.openSearchGenie('search');
            }
        }
    };

    window.addEventListener('keydown', globalListener);

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', globalListener);
    });
});
</script>
<template>
    <div class="nav-sticky">
        <div class="nav-sticky-info">
            <div>JER {{ time.toLocaleTimeString('en-US', { hour12: false }) }} (UTC +2)</div>
            <Link :href="route('homepage')">PalWeb 2.0 (BETA)</Link>
        </div>
        <div class="nav-sticky-buttons">
            <button class="material-symbols-rounded" @click.stop="NavigationStore.toggleSidebar">
                menu
            </button>
            <button class="material-symbols-rounded" @click="SearchStore.openSearchGenie('search')">
                search
            </button>
            <button class="material-symbols-rounded" @click="showThemePicker = true">
                palette
            </button>
        </div>
    </div>

    <ModalWrapper v-model="showThemePicker">
        <ThemePicker/>
    </ModalWrapper>
</template>
