<script setup>
import {ref, watch} from "vue";
import {useUserStore} from "../stores/UserStore.js";
import AppTooltip from "./AppTooltip.vue";
import {useNotifier} from "../composables/useNotifier.js";

const UserStore = useUserStore();
const Notifier = useNotifier();

const tooltip = ref(null);

const props = defineProps({
    model: Object,
    modelType: String,
});

let pinCount = ref(props.model.pinCount);
let isPinned = ref(props.model.isPinned);

const pin = async () => {
    try {
        const {data} = await axios.post(`/api/${props.modelType}s/${props.model.id}/pin`);

        if (props.modelType === 'deck') {
            pinCount.value = data.pinCount;
        }

        isPinned.value = !isPinned.value;
        Notifier.notify({
            key: `components.pin-button.notifications.pin-${
                data.isPinned ? 'added' : 'removed'
            }`,
            params: {
                model: data.modelKey
            },
        });

    } catch (error) {
        Notifier.notify({
            key: 'components.pin-button.notifications.pin-error',
            type: 'error',
        });
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
             @mousemove="tooltip.showTooltip($t('components.pin-button.tooltips.must-verify-email'), $event);"
             @mouseleave="tooltip.hideTooltip()">
            <button class="material-symbols-rounded pin-button">keep_off</button>
        </div>
        <AppTooltip ref="tooltip"/>
    </template>
</template>
