<script setup>
import DeleteButton from "./DeleteButton.vue";

const props = defineProps({
    modelType: String,
    routes: Object,
    isAdmin: Boolean,
});
</script>

<template>
    <a :href="routes.view">View Term</a>

    <template v-if="isAdmin">
        <a :href="routes.edit">Edit Term</a>
        <DeleteButton :modelType="modelType" :route="routes.delete"/>
    </template>

    <a :href="routes.usages">See Usages</a>
</template>
