<script setup>
import {ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

const props = defineProps({
    isPrivate: Boolean,
    route: String,
});

let isPrivate = ref(props.isPrivate);

const notifVisible = ref(false);
const notifContent = ref('');

const reference = ref(null);
const floating = ref(null);
const {floatingStyles} = useFloating(reference, floating, {
    placement: 'top',
    middleware: [offset(4), flip(), shift()]
});

const togglePrivacy = async () => {
    try {
        const response = await axios.patch(props.route);
        isPrivate.value = response.data.isPrivate;

        notifContent.value = response.data.message;
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);

    } catch (error) {
        console.error('Privacy Toggle Failed', error);

        notifContent.value = 'An error occurred.';
        notifVisible.value = true;
        setTimeout(() => notifVisible.value = false, 1000);
    }
};
</script>

<template>
    <img ref="reference" :class="['lock', { public: !isPrivate }]" :src="`/img/${isPrivate ? 'lock.svg' : 'lock-open.svg'}`" @click="togglePrivacy" alt="lock"/>

    <Transition name="notification">
        <div ref="floating" :style="floatingStyles" v-if="notifVisible" class="notification">
            {{ notifContent }}
        </div>
    </Transition>
</template>


