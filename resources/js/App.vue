<script setup>
import {computed} from "vue";
import {RouterView, useRoute} from "vue-router";
import Layout from "./Shared/Layout.vue";

const route = useRoute();

const section = computed(() => route.meta.section ?? 'library');
</script>

<template>
    <Layout :section="section">
        <RouterView/>
    </Layout>
</template>
