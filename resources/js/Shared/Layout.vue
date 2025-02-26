<script setup>
import Nav from "./Nav.vue";
import NotificationContainer from "../components/NotificationContainer.vue";
import SearchGenie from "./SearchGenie.vue";

defineProps({
    section: {
        type: String,
        default: 'library', // Fallback value if section is not provided
    }
});
</script>

<template>
    <div id="page-body" class="inertia" :class="section">
        <div id="page-content">
            <Nav/>

            <slot/>
            <SearchGenie/>
            <NotificationContainer/>
        </div>
    </div>
</template>
