<script setup>
import {computed} from "vue";
import {RouterView, useRoute} from "vue-router";
import Layout from "./Shared/Layout.vue";

const route = useRoute();

const section = computed(() => route.meta.section ?? 'library');

const usesLayout = computed(() => route.meta.layout !== false);
</script>

<template>
    <Layout v-if="usesLayout" :section="section">
        <RouterView/>
    </Layout>

    <RouterView v-else/>
</template>
