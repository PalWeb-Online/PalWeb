<script setup>
import NavWiki from "./NavWiki.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
    page: String,
})

const navOpen = ref(true);
</script>

<template>
    <Head :title="`Wiki: ${page}`"/>
    <div id="wiki-container">
            <div class="window-head">
                <button @click="navOpen = !navOpen" class="material-symbols-rounded">menu</button>
                <div>wiki: {{ page }}</div>
                <button @click="router.get(route('homepage'))" class="material-symbols-rounded">close</button>
            </div>
        <div class="wiki-body" :class="{ 'nav-open': navOpen }">
            <NavWiki :page="page"/>
            <div class="wiki-content-wrapper">
                <slot/>
            </div>
        </div>
    </div>
</template>
