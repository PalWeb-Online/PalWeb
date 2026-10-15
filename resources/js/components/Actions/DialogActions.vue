<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {useDialog} from "../../composables/dialogs/useDialog.js";
import {useRoute} from "vue-router";

const UserStore = useUserStore();
const currentRoute = useRoute();
const {deleteDialog} = useDialog();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <RouterLink v-if="currentRoute.name !== 'dialogs.show'"
              :to="route('dialogs.show', model.id)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.dialog') }) }}
        </RouterLink>
        <template v-if="UserStore.isAdmin">
            <RouterLink :to="{ name: 'speech-maker.dialog', params: { dialog: model.id } }" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.dialog') }) }}
            </RouterLink>
            <button @click="deleteDialog(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.dialog') }) }}
            </button>
        </template>
        <RouterLink v-if="model.lesson" :to="{ name: 'lessons.show', params: { lesson: model.lesson.global_position } }" role="menuitem"
              tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.lesson') }) }}
        </RouterLink>
    </ContextActions>
</template>
