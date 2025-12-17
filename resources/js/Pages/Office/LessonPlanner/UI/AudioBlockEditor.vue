<script setup>
const props = defineProps({
    block: {type: Object, required: true},
});
</script>

<template>
    <div class="block--audio">
        <audio v-if="props.block.media" :src="props.block.media" controls/>
        <input v-model="props.block.media"
               :class="{ 'invalid': !props.block.media }"
               style="width: 100%"
               placeholder="URL"/>
    </div>
</template>
