<script setup>
defineProps({
    block: { type: Object, required: true }
})
</script>

<template>
    <div class="block--audio" style="width: 100%">
<!--        <p>Listen to the following recording to answer the subsequent exercises.</p>-->
        <audio :src="block.media" controls/>
    </div>
</template>
