<script setup>
import {useCard} from "../../composables/cards/useCard.js";

const props = defineProps({
    model: Object,
});

const {deleteCard, masterCard, toggleSuspend, resetCard} = useCard();
</script>

<template>
        <button @click="masterCard(model)" role="menuitem" tabindex="-1">
            Master Card
        </button>
        <button @click="toggleSuspend(model)" role="menuitem" tabindex="-1">
            {{ model.suspended_at ? 'Restore' : 'Suspend' }} Card
        </button>
        <button @click="resetCard(model)" role="menuitem" tabindex="-1">
            Reset Card
        </button>
        <button @click="deleteCard(model)" role="menuitem" tabindex="-1">
            Delete Card
        </button>
</template>
