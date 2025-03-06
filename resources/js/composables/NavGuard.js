import {onMounted, onUnmounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {router} from "@inertiajs/vue3";

export function useNavGuard(hasNavigationGuard) {
    const showAlert = ref(false);
    const pendingVisit = ref(null);
    const isSkippingGuard = ref(false);

    const handleConfirm = () => {
        showAlert.value = false;

        if (pendingVisit.value) {
            isSkippingGuard.value = true;

            const { href } = pendingVisit.value.url;
            const { method, data, headers, replace, preserveState, preserveScroll } = pendingVisit.value;

            Inertia.visit(href, {
                method,
                data,
                headers,
                replace,
                preserveState,
                preserveScroll,
            });

            pendingVisit.value = null;
        }

        setTimeout(() => (isSkippingGuard.value = false), 0);
    };

    const handleCancel = () => {
        showAlert.value = false;
        pendingVisit.value = null;
    };

    onMounted(async () => {
        const handleBeforeUnload = (event) => {
            if (hasNavigationGuard.value) {
                event.preventDefault();
                event.returnValue = '';
            }
        };

        window.addEventListener('beforeunload', handleBeforeUnload);

        const unsubscribe = router.on('before', (event) => {

            if (isSkippingGuard.value) {
                return;
            }

            if (hasNavigationGuard.value) {
                event.preventDefault();
                showAlert.value = true;
                pendingVisit.value = event.detail.visit;
                return false;
            }
        });

        onUnmounted(() => {
            unsubscribe();
            window.removeEventListener('beforeunload', handleBeforeUnload);
        });
    });

    return {
        showAlert,
        handleConfirm,
        handleCancel,
    };
}
