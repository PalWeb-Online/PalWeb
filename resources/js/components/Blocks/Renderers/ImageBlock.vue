<script setup>
defineProps({
    block: { type: Object, required: true }
})
</script>

<template>
    <div class="block--image" style="width: 100%">
<!--        <p>Refer to the following image to answer the subsequent exercises.</p>-->
        <img :src="block.media" alt="No Image Found"/>
    </div>
</template>
