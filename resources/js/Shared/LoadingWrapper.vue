<script setup>
import ZatuneLoading from './ZatuneLoading.vue';

defineProps({
    isLoading: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div v-if="isLoading" id="app-body">
        <ZatuneLoading/>
    </div>
    <slot v-else/>
</template>

<style scoped lang="scss">
</style>
