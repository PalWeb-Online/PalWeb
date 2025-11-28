<script setup>
import {ref, watch} from "vue";
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";
import AppTooltip from "./AppTooltip.vue";
import {useNotificationStore} from "../stores/NotificationStore.js";

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const tooltip = ref(null);

const props = defineProps({
    model: Object,
    modelType: String,
});

let pinCount = ref(props.model.pinCount);
let isPinned = ref(props.model.isPinned);

const pin = async () => {
    try {
        const response = await axios.post(route(`${props.modelType}s.pin`, props.model.id));

        if (props.modelType === 'deck') {
            pinCount.value = response.data.pinCount;
        }

        isPinned.value = !isPinned.value;
        NotificationStore.addNotification(response.data.message);

    } catch (error) {
        console.error('Pin Failed', error);
    }
};

watch(() => props.model, (newModel) => {
    pinCount.value = newModel.pinCount;
    isPinned.value = newModel.isPinned;
}, {
    deep: true
});
</script>

<template>
    <template v-if="UserStore.isUser">
        <div v-if="UserStore.user.is_verified"
             class="pin-button-wrapper" :class="{ pinned: isPinned }" @click="pin">
            <button class="material-symbols-rounded pin-button">{{ isPinned ? 'keep' : 'keep_off' }}</button>
            <div class="pin-counter" v-if="pinCount > 1">{{ pinCount }}</div>
        </div>
        <div v-else
             class="pin-button-wrapper"
             @mousemove="tooltip.showTooltip('You must verify your email to enable Pins.', $event);"
             @mouseleave="tooltip.hideTooltip()">
            <button class="material-symbols-rounded pin-button">keep_off</button>
        </div>
        <AppTooltip ref="tooltip"/>
    </template>
</template>


