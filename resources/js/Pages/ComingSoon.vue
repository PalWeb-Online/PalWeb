<script setup>
import Layout from "../Shared/Layout.vue";
import {computed} from "vue";
import AppTip from "../components/AppTip.vue";

defineOptions({
    layout: Layout
});
</script>
<template>
    <Head title="Coming Soon"/>
    <div id="app-head">
        <h1>Coming Soon</h1>
    </div>
    <div id="app-body">
        <AppTip>
            <p>Sorry, the page you are looking for is currently under construction. Come back soon.</p>
        </AppTip>
    </div>
</template>
