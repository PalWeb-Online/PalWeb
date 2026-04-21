<script setup>
import {onMounted, ref} from "vue";
import {disableWebPush, enableWebPush} from "../push.js";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {autoUpdate, offset, flip, shift, useFloating} from "@floating-ui/vue";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();
const showSubscribeHint = ref(false);

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

const dismissSubscribeHint = () => {
    showSubscribeHint.value = false;
};

const toggleNotifications = async () => {
    if (!NotificationStore.currentBrowserSubscribed) {
        try {
            await enableWebPush();
            dismissSubscribeHint();
            alert('Notifications enabled. You may still need to enable notifications from the browser on your device.');
            await NotificationStore.refreshCurrentBrowserSubscriptionState();

        } catch (error) {
            console.error(error);
            alert(error.message || 'Could not enable notifications.');
        }
    } else {
        try {
            await disableWebPush();
            alert('Notifications disabled.');
            await NotificationStore.refreshCurrentBrowserSubscriptionState();
        } catch (error) {
            console.error(error);
            alert(error.message || 'Could not disable notifications.');
        }
    }
};

onMounted(async () => {
    await NotificationStore.refreshCurrentBrowserSubscriptionState();
    showSubscribeHint.value = !NotificationStore.currentBrowserSubscribed;
});
</script>

<template>
    <div v-if="UserStore.isUser" class="app-hint-wrapper">
        <button
            ref="reference"
            class="material-symbols-rounded"
            @click="toggleNotifications"
            type="button"
            aria-label="Enable Notifications"
        >
            {{ NotificationStore.currentBrowserSubscribed ? 'notifications_active' : 'notifications_off' }}
        </button>

        <Teleport to="body">
            <div v-if="showSubscribeHint"
                 ref="floating"
                 :style="floatingStyles"
                 class="app-tooltip app-hint"
                 role="dialog"
                 aria-live="polite"
            >
                <div class="app-hint__text">
                    Click the Notification Bell to get a daily reminder to review your Cards!
                </div>

                <button
                    type="button"
                    class="app-hint__dismiss"
                    @click="dismissSubscribeHint"
                >
                    Dismiss
                </button>
            </div>
        </Teleport>
    </div>
</template>
