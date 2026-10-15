<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {useSentence} from "../../composables/sentences/useSentence.js";
import {useRoute} from "vue-router";

const UserStore = useUserStore();
const currentRoute = useRoute();
const {deleteSentence} = useSentence();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <RouterLink v-if="currentRoute.name !== 'sentences.show'"
              :to="route('sentences.show', model.id)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.sentence') }) }}
        </RouterLink>
        <template v-if="UserStore.isAdmin">
            <RouterLink :to="{ name: 'speech-maker.sentence', params: { sentence: model.id } }" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.sentence') }) }}
            </RouterLink>
            <button @click="deleteSentence(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.sentence') }) }}
            </button>
        </template>
    </ContextActions>
</template>
