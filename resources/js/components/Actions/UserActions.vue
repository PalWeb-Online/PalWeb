<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUser} from "../../composables/users/useUser.js";


const props = defineProps({
    model: Object,
});

const {isStudent, toggleStudentRole} = useUser(props.model);
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link :href="route('users.edit', model.username)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.edit', { model: $t('actions.models.user') }) }}
        </Link>
        <button @click="toggleStudentRole(model.id)" role="menuitem" tabindex="-1">
            {{ $t(isStudent ? 'actions.user.revoke-student-role' : 'actions.user.grant-student-role') }}
        </button>
    </ContextActions>
</template>
