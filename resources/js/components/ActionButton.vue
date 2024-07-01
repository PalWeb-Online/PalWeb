<script setup>
import {ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

const props = defineProps({
    id: Number,
    isPinned: Boolean,
});

let isPinned = ref(props.isPinned);
const pinRoute = `/dictionary/terms/${props.id}/pin`;

const notifVisible = ref(false);
const notifContent = ref('');

const reference = ref(null);
const floating = ref(null);
const {x, y, strategy} = useFloating(reference, floating, {
    placement: 'right',
    middleware: [offset(8), flip(), shift()]
});

const pin = async () => {
    try {
        const response = await axios.post(pinRoute, {id: props.id});
        isPinned.value = response.data.isPinned;
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
    <form ref="reference">
        <button @click.prevent="pin">
            {{ isPinned ? 'Unpin' : 'Pin' }} Term
        </button>
    </form>
    <Transition name="notification">
        <div ref="floating" class="notification"
             :style="{ position: strategy, top: y ? `${y}px` : '', left: x ? `${x}px` : '' }"
             v-if="notifVisible">
            {{ notifContent }}
        </div>
    </Transition>
</template>


