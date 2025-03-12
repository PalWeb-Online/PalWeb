<script setup>
import Nav from "./Nav.vue";
import NotificationContainer from "../components/NotificationContainer.vue";
import SearchGenie from "./SearchGenie.vue";
import SearchGenieTrigger from "./SearchGenieTrigger.vue";

defineProps({
    section: {
        type: String,
        default: 'library',
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
    </div>
</template>
