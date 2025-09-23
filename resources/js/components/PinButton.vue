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
    floating: {type: Boolean, default: false},
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
        <template v-if="floating">
            <template v-if="UserStore.user.is_verified">
                <img :class="['pin', { unpinned: !isPinned }]" src="/img/pin.svg" @click="pin" alt="pin"/>
                <div v-if="pinCount > 1" class="pin-counter">
                    <img src="/img/heart.svg" alt="heart"/>
                    <div>{{ pinCount }}</div>
                </div>
            </template>
            <template v-else>
                <img class="pin unpinned" src="/img/pin.svg" alt="pin"
                     @mousemove="tooltip.showTooltip('You must verify your email to enable Pins.', $event);"
                     @mouseleave="tooltip.hideTooltip()"/>
            </template>
        </template>
        <template v-else>
            <template v-if="UserStore.user.is_verified">
                <button class="material-symbols-rounded" :class="{ pinned: isPinned }" style="position: relative;" @click="pin">
                    {{ isPinned ? 'keep' : 'keep_off' }}
                    <div v-if="pinCount > 1" class="pin-counter" style="top: auto; left: -3.6rem;">
                        <img src="/img/heart.svg" alt="heart"/>
                        <div>{{ pinCount }}</div>
                    </div>
                </button>
            </template>
            <template v-else>
                <button class="material-symbols-rounded"
                        @mousemove="tooltip.showTooltip('You must verify your email to enable Pins.', $event);"
                        @mouseleave="tooltip.hideTooltip()">
                    keep_off
                </button>
            </template>
        </template>
        <AppTooltip ref="tooltip"/>
    </template>
</template>


