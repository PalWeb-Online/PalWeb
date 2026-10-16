<script setup>
import ContextActions from "./ContextActions.vue";
import {useUser} from "../../composables/users/useUser.js";
import {useNotifier} from "../../composables/useNotifier.js";

const props = defineProps({
    model: Object,
});

const {isStudent, user} = useUser(props.model);

const Notifier = useNotifier();

const toggleStudentRole = async () => {
    try {
        const {data} = await axios.patch(route('api.users.roles.toggle-student', user.value.id));

        user.value.roles = data.user.roles;
        Notifier.notify({
            key: isStudent.value
                ? 'user.notifications.role-granted'
                : 'user.notifications.role-revoked',
        });

    } catch (e) {
        Notifier.notify({
            key: 'user.notifications.role-toggle-error',
            type: 'error',
        });
    }
};
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <RouterLink :to="{ name: 'users.edit', params: { user: model.id } }" role="menuitem" tabindex="-1">
            {{ $t('actions.common.edit', { model: $t('actions.models.user') }) }}
        </RouterLink>
        <button @click="toggleStudentRole(model.id)" role="menuitem" tabindex="-1">
            {{ $t(isStudent ? 'actions.user.revoke-student-role' : 'actions.user.grant-student-role') }}
        </button>
    </ContextActions>
</template>
