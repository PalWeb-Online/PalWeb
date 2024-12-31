<script setup>
import {ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";
import {eventBus} from '../../../utils/eventBus.js';

const props = defineProps({
    id: Number,
    model: String,
    isPinned: Boolean,
});

let isPinned = ref(props.isPinned);

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
        const response = await axios.post('/community/decks/' + props.id + '/pin');
        isPinned.value = response.data.isPinned;

        notifContent.value = response.data.message;
        notifVisible.value = true;
        setTimeout(() => {
            notifVisible.value = false;
            eventBus.emit('pinnedModel', {id: props.id, model: props.model, isPinned: response.data.isPinned});
        }, 1000);


    } catch (error) {
        console.error('Pin Failed', error);

        notifContent.value = 'An error occurred.';
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);
    }
};
</script>

<template>
    <img ref="reference" :class="['pin', { unpinned: !isPinned }]" src="/img/pin.svg" @click="pin" alt="pin"/>

    <Transition name="notification">
        <div ref="floating" :style="floatingStyles" v-if="notifVisible" class="notification">
            {{ notifContent }}
        </div>
    </Transition>
</template>


