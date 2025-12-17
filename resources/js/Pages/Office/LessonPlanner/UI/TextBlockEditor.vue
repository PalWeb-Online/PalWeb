<script setup>
const props = defineProps({
    block: {type: Object, required: true},
});
</script>

<template>
    <div class="block--text">
        <textarea v-model="props.block.content" :class="{ 'invalid': !props.block.content }"/>
    </div>
</template>
