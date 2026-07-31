<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {useActivity} from "../../composables/activities/useActivity.js";

const UserStore = useUserStore();
const {deleteActivity} = useActivity();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Academy/Activities/Show'"
              :href="route('activities.show', model.id)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.activity') }) }}
        </Link>
        <template v-if="UserStore.isAdmin">
            <Link :href="route('lesson-planner.lesson-activity', model.lesson.id)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.activity') }) }}
            </Link>
            <button @click="deleteActivity(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.activity') }) }}
            </button>
        </template>
        <Link v-if="model.lesson"
              :href="route('lessons.show', model.lesson.global_position)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.lesson') }) }}
        </Link>
        <Link :href="route('scores.history', { scorable_type: 'activity', scorable_id: model.id })" role="menuitem"
              tabindex="-1">
            {{ $t('actions.common.view-scores') }}
        </Link>
    </ContextActions>
</template>
