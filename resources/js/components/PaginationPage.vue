<script setup>
import LoadingSpinner from '../Shared/LoadingSpinner.vue';

defineProps({
    isLoading: {type: Boolean, default: false},
})
</script>

<template>
    <div class="pagination-page">
        <LoadingSpinner v-if="isLoading"/>
        <div class="model-list index-list" :class="{ 'is-loading': isLoading }">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">
.pagination-page {
    position: relative;

    .loading-spinner {
        position: absolute;
        margin: auto;
        inset: 0;
        z-index: 2;
        opacity: 1;
    }
}
</style>
