<script setup>
import ContextActions from "./ContextActions.vue";
import {useUser} from "../../composables/users/useUser.js";


const props = defineProps({
    model: Object,
});

const {isStudent, toggleStudentRole} = useUser(props.model);
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <RouterLink :to="{ name: 'users.edit', params: { user: model.username } }" role="menuitem" tabindex="-1">
            {{ $t('actions.common.edit', { model: $t('actions.models.user') }) }}
        </RouterLink>
        <button @click="toggleStudentRole(model.id)" role="menuitem" tabindex="-1">
            {{ $t(isStudent ? 'actions.user.revoke-student-role' : 'actions.user.grant-student-role') }}
        </button>
    </ContextActions>
</template>
