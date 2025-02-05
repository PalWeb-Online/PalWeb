<script setup>
import {computed, ref, watch} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
    modelType: String,
});

let pinCount = ref(props.model.pinCount);
const isPinned = computed(() =>
    UserStore.user.pinned[`${props.modelType}s`].some(item => item.id === props.model.id)
);

const notifVisible = ref(false);
const notifContent = ref('');

const reference = ref(null);
const floating = ref(null);
const {floatingStyles} = useFloating(reference, floating, {
    placement: 'top',
    middleware: [offset(-4), flip(), shift()]
});

const pin = async () => {
    try {
        const response = await axios.post(route(`${props.modelType}s.pin`, props.model.id));

        if (props.modelType === 'deck') {
            pinCount.value = response.data.pinCount;
        }

        if (!isPinned.value) {
            UserStore.user.pinned[`${props.modelType}s`].push(props.model);

        } else {
            UserStore.user.pinned[`${props.modelType}s`].splice(
                UserStore.user.pinned[`${props.modelType}s`].findIndex(item => item.id === props.model.id), 1
            )
        }

        notifContent.value = response.data.message;
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);

    } catch (error) {
        console.error('Pin Failed', error);

        notifContent.value = 'An error occurred.';
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);
    }
};
</script>

<template>
    <template v-if="UserStore.isUser">
        <img ref="reference" :class="['pin', { unpinned: !isPinned }]" src="/img/pin.svg" @click="pin" alt="pin"/>
        <div v-if="pinCount > 1" class="pin-counter">
            <img src="/img/heart.svg" alt="heart"/>
            <div>{{ pinCount }}</div>
        </div>

        <Transition name="notification">
            <div ref="floating" :style="floatingStyles" v-if="notifVisible" class="notification">
                {{ notifContent }}
            </div>
        </Transition>
    </template>
</template>


