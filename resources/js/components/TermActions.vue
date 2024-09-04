<script setup>
import DeckToggleButton from "./DeckToggleButton.vue";
import DeleteButton from "./DeleteButton.vue";

const props = defineProps({
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
        <DeleteButton :deleteRoute="routes.delete"/>
    </template>

    <template v-if="isUser">
        <div class="action-divider"></div>
        <DeckToggleButton :userDecks="userDecks" :deckToggleRoute="routes.deckToggle"/>
    </template>
</template>
