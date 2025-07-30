<script setup>
import NavWiki from "./NavWiki.vue";
import {ref} from "vue";
import AppTip from "../../components/AppTip.vue";

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
        <AppTip dismissable>
            <p>The <b>Wiki</b> is an extensive repository of information about Palestinian Arabic & PalWeb. Due to time
                constraints, these pages are not updated very often, especially those that pertain to Palestinian Arabic
                as such. Be aware that some of this information may be outdated or incomplete. If you are interested in
                writing or co-writing about Palestinian Arabic for PalWeb, send an email to <b>adrian@palweb.app</b>.
            </p>
        </AppTip>
        <div class="wiki-body" :class="{ 'nav-open': navOpen }">
            <NavWiki :page="page"/>
            <div class="wiki-content-wrapper">
                <slot/>
            </div>
        </div>
    </div>
</template>
