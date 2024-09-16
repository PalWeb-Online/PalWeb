<script setup>
import {ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

const props = defineProps({
    isPinned: Boolean,
    pinCount: Number,
    route: String,
    imageURL: String,
});

let isPinned = ref(props.isPinned);
let pinCount = ref(props.pinCount);

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
        const response = await axios.post(props.route);
        isPinned.value = response.data.isPinned;

        if (response.data.pinCount) {
            pinCount.value = response.data.pinCount;
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
    <img ref="reference" :class="['pin', { unpinned: !isPinned }]" :src="`${imageURL}/pin.svg`" @click="pin" alt="pin"/>
    <div v-if="pinCount > 1" class="pin-counter">
        <img :src="`${imageURL}/heart.svg`" alt="heart"/>
        <div>{{ pinCount }}</div>
    </div>

    <Transition name="notification">
        <div ref="floating" :style="floatingStyles" v-if="notifVisible" class="notification">
            {{ notifContent }}
        </div>
    </Transition>
</template>


