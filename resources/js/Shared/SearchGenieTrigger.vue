<script setup>
import {onBeforeUnmount, onMounted} from 'vue';
import {useSearchStore} from '../stores/SearchStore.js';

const SearchStore = useSearchStore();

onMounted(() => {
    const globalListener = (event) => {
        const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;

        if ((isMac && event.metaKey && event.key === 'k') || (!isMac && event.ctrlKey && event.key === 'k')) {
            event.preventDefault();
            if (SearchStore.data.isOpen && SearchStore.data.action === 'search') {
                SearchStore.closeSearchGenie();

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
    <div class="sg-trigger-wrapper">
        <button class="sg-trigger" @click="SearchStore.openSearchGenie('search')">
            <img src="/img/key_cmd.svg" alt="Command Key"/>
            <img src="/img/key_k.svg" alt="Letter K Key"/>
        </button>
        <img class="search" src="/img/search.svg" alt="Search"/>
    </div>
</template>
