<script setup>
import {useCard} from "../../composables/cards/useCard.js";

const props = defineProps({
    model: Object,
});

const {deleteCard, masterCard, toggleSuspend, resetCard} = useCard();
</script>

<template>
        <button @click="masterCard(model)" role="menuitem" tabindex="-1">
            {{ $t('actions.card.master') }}
        </button>
        <button @click="toggleSuspend(model)" role="menuitem" tabindex="-1">
            {{ $t(model.suspended_at ? 'actions.card.restore' : 'actions.card.suspend') }}
        </button>
        <button @click="resetCard(model)" role="menuitem" tabindex="-1">
            {{ $t('actions.card.reset') }}
        </button>
        <button @click="deleteCard(model)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.delete', { model: $t('actions.models.card') }) }}
        </button>
</template>
