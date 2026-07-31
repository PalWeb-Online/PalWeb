import {computed, unref} from "vue";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useI18n} from "vue-i18n";

export function useUser(user) {
    const {t} = useI18n();
    const NotificationStore = useNotificationStore();

    const resolvedUser = computed(() => unref(user) ?? null);

    const isAdmin = computed(() => resolvedUser.value?.roles?.includes('admin') ?? false);
    const isStudent = computed(() => resolvedUser.value?.roles?.includes('student') ?? false);
    const isUser = computed(() => !!resolvedUser.value);

    const highestRole = computed(() => {
        if (isAdmin.value) return 'admin';
        if (isStudent.value) return 'student';
        if (isUser.value) return 'pal';
        return 'guest';
    });

    const toggleStudentRole = async () => {
        try {
            const {data} = await axios.patch(route('api.users.roles.toggle-student', resolvedUser.value.id));

            resolvedUser.value.roles = data.user.roles;
            isStudent.value
                ? NotificationStore.addNotification(t('user.notifications.role-granted'))
                : NotificationStore.addNotification(t('user.notifications.role-revoked'));

        } catch (e) {
            console.error(t('user.notifications.role-toggle-error'), e);
            NotificationStore.addNotification(t('user.notifications.role-toggle-error'), 'error');
        }
    };

    return {isAdmin, isStudent, isUser, highestRole, toggleStudentRole};
}
