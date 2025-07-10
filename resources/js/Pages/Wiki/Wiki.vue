<script setup>
import NavWiki from "./NavWiki.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
    page: String,
})

const navOpen = ref(false);
</script>

<template>
    <Head :title="`Wiki: ${page}`"/>
    <div id="app-head">
        <h1>wiki</h1>
    </div>
    <div id="wiki-container">
        <div class="window-section-head">
            <button @click="navOpen = !navOpen" class="material-symbols-rounded menu" :class="{'active': navOpen}">
                menu
            </button>
            <h1>{{ page }}</h1>
        </div>
        <div class="wiki-body" :class="{ 'nav-open': navOpen }">
            <NavWiki :page="page"/>
            <div class="wiki-content-wrapper">
                <slot/>
            </div>
        </div>
    </div>
</template>
