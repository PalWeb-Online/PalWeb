<script setup>
import DeleteButton from "./DeleteButton.vue";

const props = defineProps({
    modelType: String,
    routes: Object,
    isAdmin: Boolean,
});
</script>

<template>
    <a :href="routes.view">View Sentence</a>

    <template v-if="isAdmin">
        <a :href="routes.edit">Edit Sentence</a>
        <DeleteButton :modelType="modelType" :route="routes.delete"/>
    </template>
</template>
