<script setup>
const props = defineProps({
    block: {type: Object, required: true},
});
</script>

<template>
    <div class="block-editor--image">
        <img v-if="props.block.media" :src="props.block.media" alt="No Image Found"/>

        <input v-model="props.block.media"
               :class="{ 'invalid': !props.block.media }"
               style="width: 100%"
               placeholder="URL"/>
    </div>
</template>
