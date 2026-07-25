<script setup>
import {route} from 'ziggy-js';
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";
import CardActions from "./CardActions.vue";
import {useTerm} from "../../composables/terms/useTerm.js";

const UserStore = useUserStore();
const {deleteTerm} = useTerm();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Library/Terms/Show'"
              :href="route('terms.show', model.slug)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.term') }) }}
        </Link>
        <Link v-if="model.root" :href="route('roots.show', model.root.id)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.root') }) }}
        </Link>
        <template v-if="UserStore.isAdmin">
            <Link :href="route('word-logger.term', model.id)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.term') }) }}
            </Link>
            <button @click="deleteTerm(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.term') }) }}
            </button>
        </template>

        <CardActions v-if="model.card" :model="model.card"/>
    </ContextActions>
</template>
