<script setup>
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";
import CardActions from "./CardActions.vue";
import {useTerm} from "../../composables/terms/useTerm.js";
import {useRoute} from "vue-router";

const UserStore = useUserStore();
const currentRoute = useRoute();
const {deleteTerm} = useTerm();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <RouterLink v-if="currentRoute.name !== 'terms.show'"
              :to="{ name: 'terms.show', params: { term: model.slug } }" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.term') }) }}
        </RouterLink>
        <RouterLink v-if="model.root" :to="{ name: 'roots.show', params: { root: model.root.id } }" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.root') }) }}
        </RouterLink>
        <template v-if="UserStore.isAdmin">
            <RouterLink :to="{ name: 'word-logger.term', params: { term: model.id } }" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.term') }) }}
            </RouterLink>
            <button @click="deleteTerm(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.term') }) }}
            </button>
        </template>

        <CardActions v-if="model.card" :model="model.card"/>
    </ContextActions>
</template>
