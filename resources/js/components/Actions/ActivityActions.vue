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
        <RouterLink v-if="$page.component !== 'Academy/Activities/Show'"
              :to="route('activities.show', model.id)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.activity') }) }}
        </RouterLink>
        <template v-if="UserStore.isAdmin">
            <RouterLink :to="{ name: 'lesson-planner.lesson-activity', params: { lesson: model.lesson.id } }" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.activity') }) }}
            </RouterLink>
            <button @click="deleteActivity(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.activity') }) }}
            </button>
        </template>
        <RouterLink v-if="model.lesson"
              :to="{ name: 'lessons.show', params: { lesson: model.lesson.global_position } }" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.lesson') }) }}
        </RouterLink>
        <RouterLink :to="{
            name: 'scores.history',
            params: {
                scorable_type: 'activity',
                scorable_id: model.id
            }
        }" role="menuitem"
              tabindex="-1">
            {{ $t('actions.common.view-scores') }}
        </RouterLink>
    </ContextActions>
</template>
