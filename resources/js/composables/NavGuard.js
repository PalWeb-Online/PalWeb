import {onMounted, onUnmounted, ref} from "vue";
import {router} from "@inertiajs/vue3";

export function useNavGuard(hasNavigationGuard) {
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

            const { href } = pendingVisit.value.url;
            const { method, data, headers, replace, preserveState, preserveScroll } = pendingVisit.value;

            router.visit(href, {
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
            if (isSkippingGuard.value  || event.detail.visit.method !== 'get') {
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
        skipNext,
        handleConfirm,
        handleCancel,
    };
}
