<script setup>
defineProps({
    block: { type: Object, required: true }
})
</script>

<template>
    <div class="block--audio">
        <p>Listen to the following recording to answer the subsequent exercises.</p>
        <audio :src="block.media" controls/>
    </div>
</template>
