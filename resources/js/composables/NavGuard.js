import {onMounted, onUnmounted, ref} from "vue";
import {useRouter} from "vue-router";

export function useNavGuard(hasNavigationGuard) {
    const router = useRouter();
    const showAlert = ref(false);
    const pendingVisit = ref(null);
    const isSkippingGuard = ref(false);

    const skipNext = () => {
        isSkippingGuard.value = true;
    };

    const handleConfirm = () => {
        showAlert.value = false;

        if (pendingVisit.value) {
            isSkippingGuard.value = true;

            router.push(pendingVisit.value.fullPath);

            pendingVisit.value = null;
        }

        setTimeout(() => (isSkippingGuard.value = false), 0);
    };

    const handleCancel = () => {
        showAlert.value = false;
        pendingVisit.value = null;
    };

    onMounted(() => {
        // Blocks browser unloads and Vue Router navigations while the form is dirty.

        const handleBeforeUnload = (event) => {
            if (hasNavigationGuard.value) {
                event.preventDefault();
                event.returnValue = '';
            }
        };

        window.addEventListener('beforeunload', handleBeforeUnload);

        const removeGuard = router.beforeEach((to) => {
            if (isSkippingGuard.value) {
                return;
            }

            if (hasNavigationGuard.value) {
                showAlert.value = true;
                pendingVisit.value = to;
                return false;
            }
        });

        onUnmounted(() => {
            removeGuard();
            window.removeEventListener('beforeunload', handleBeforeUnload);
        });
    });

    return {
        showAlert,
        skipNext,
        handleConfirm,
        handleCancel,
    };
}
