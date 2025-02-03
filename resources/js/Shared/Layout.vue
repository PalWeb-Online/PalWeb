<script setup>
import Nav from "./Nav.vue";
import NotificationContainer from "../components/NotificationContainer.vue";
</script>

<template>
    <Nav/>

    <slot />
    <NotificationContainer />
</template>
