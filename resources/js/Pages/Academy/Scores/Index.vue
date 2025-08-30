<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";

defineOptions({
    layout: Layout
});
</script>
<template>
    <Head title="Academy: myProgress"/>
    <div id="app-head">
        <Link :href="route('scores.index')"><h1>my Progress</h1></Link>
    </div>
</template>
