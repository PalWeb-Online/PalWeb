<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";
import {autoUpdate, offset, flip, shift, useFloating} from "@floating-ui/vue";

const showInstallButton = ref(false);
const showInstallHint = ref(false);
let deferredInstallPrompt = null;

const reference = ref(null);
const floating = ref(null);

const {floatingStyles} = useFloating(reference, floating, {
    placement: 'bottom',
    whileElementsMounted: autoUpdate,
    middleware: [
        offset(12),
        flip({
            padding: 8,
        }),
        shift({
            padding: 8,
        }),
    ],
});

const isAppInstalled = () => {
    return window.matchMedia('(display-mode: window-controls-overlay)').matches
        || window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;
};

const dismissInstallHint = () => {
    showInstallHint.value = false;
};

const installApp = async () => {
    if (!deferredInstallPrompt) {
        alert('App installation is not available in this browser.');
        return;
    }

    deferredInstallPrompt.prompt();
    const choice = await deferredInstallPrompt.userChoice;

    if (choice?.outcome === 'accepted') {
        showInstallButton.value = false;
        showInstallHint.value = false;
    }

    deferredInstallPrompt = null;
};

onMounted(() => {
    if (isAppInstalled()) {
        showInstallButton.value = false;
        showInstallHint.value = false;
        return;
    }

    const handleBeforeInstallPrompt = (event) => {
        event.preventDefault();
        deferredInstallPrompt = event;
        showInstallButton.value = true;
        showInstallHint.value = true;
    };

    const handleAppInstalled = () => {
        deferredInstallPrompt = null;
        showInstallButton.value = false;
        showInstallHint.value = false;
    };

    window.addEventListener("beforeinstallprompt", handleBeforeInstallPrompt);
    window.addEventListener("appinstalled", handleAppInstalled);

    onBeforeUnmount(() => {
        window.removeEventListener("beforeinstallprompt", handleBeforeInstallPrompt);
        window.removeEventListener("appinstalled", handleAppInstalled);
    });
});
</script>

<template>
    <div v-if="showInstallButton" class="app-hint-wrapper">
        <button
            ref="reference"
            class="material-symbols-rounded install-button"
            @click="installApp"
            type="button"
            aria-label="Install App"
        >
            download
        </button>

        <Teleport to="body">
            <div v-if="showInstallHint"
                 ref="floating"
                 :style="floatingStyles"
                 class="app-tooltip app-hint"
                 role="dialog"
                 aria-live="polite"
            >
                <div class="app-hint__text">
                    Click the Install Button to download PalWeb to your device for easier access!
                </div>

                <button
                    type="button"
                    class="app-hint__dismiss"
                    @click="dismissInstallHint"
                >
                    Dismiss
                </button>
            </div>
        </Teleport>
    </div>
</template>

<style scoped lang="scss">
.app-hint-wrapper {
    background: white;
}

.install-button {
    font-size: 2.0rem;
    color: var(--color-dark-primary);
    width: 3.6rem;
    height: 100%;
}
</style>
