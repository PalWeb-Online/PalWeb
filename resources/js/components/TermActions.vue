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
    <a :href="routes.usages">See Usages</a>

    <template v-if="isAdmin">
        <div class="action-divider"></div>
        <a :href="routes.edit">Edit Term</a>
        <DeleteButton :modelType="modelType" :route="routes.delete"/>
    </template>
</template>
