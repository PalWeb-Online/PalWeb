<script setup>
import {onMounted, ref} from 'vue';
import {offset, flip, shift, useFloating} from "@floating-ui/vue";
import VanillaTilt from 'vanilla-tilt';

const props = defineProps({
    badge: Object,
});

const isOpen = ref(false);
const reference = ref(null);
const floating = ref(null);
const {floatingStyles} = useFloating(reference, floating, {
    placement: 'top',
    middleware: [offset(8), flip(), shift()]
});

onMounted(() => {
    if (props.badge.enabled) {
        VanillaTilt.init(reference.value, {
            max: 20,
            speed: 400,
            scale: 1,
            glare: true,
        });
    }

    reference.value.addEventListener('mouseenter', () => {
        isOpen.value = true;
    });
    reference.value.addEventListener('mouseleave', () => {
        isOpen.value = false;
    });
});

</script>

<template>
    <div ref="reference" :class="['badge', badge.enabled ? '' : 'disabled']">
        <img :alt="badge.name" :src="badge.image"/>
    </div>
    <div ref="floating" v-if="isOpen" :style="floatingStyles" class="notification badge-data">
        <div>{{ badge.enabled ? badge.name : '???' }}</div>
        <div>{{ badge.description }}</div>
    </div>
</template>

