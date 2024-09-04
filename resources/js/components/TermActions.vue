<script setup>
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import DeleteButton from "./DeleteButton.vue";

const props = defineProps({
    modelType: String,
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,

    // ActionButtons
    userDecks: Object,
});
</script>

<template>
    <a :href="routes.view">Look Up</a>
    <a :href="routes.usages">See Usages</a>

    <template v-if="isAdmin">
        <div class="action-divider"></div>
        <a :href="routes.edit">Edit Term</a>
        <DeleteButton :modelType="modelType" :route="routes.delete"/>
    </template>

    <template v-if="isUser">
        <div class="action-divider"></div>
        <TermDeckToggleButton :userDecks="userDecks" :route="routes.deckToggle"/>
    </template>
</template>
