<script setup>
const props = defineProps({
    block: {type: Object, required: true},
});
</script>

<template>
    <div class="block-editor--text">
        <textarea v-model="props.block.content" :class="{ 'invalid': !props.block.content }"/>
    </div>
</template>

<style scoped lang="scss">
.block-editor--text {
    textarea {
        padding: 1.2rem 1.6rem;
        font-family: var(--body-font);
        font-size: 1.6rem;
        min-height: 16rem;
        resize: vertical;
    }
}
</style>
