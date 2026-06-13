<script setup>
defineProps({
    block: {type: Object, required: true}
})
</script>

<template>
    <div class="block--image">
        <!--        <p>Refer to the following image to answer the subsequent exercises.</p>-->
        <img :src="block.media" alt="No Image Found"/>
    </div>
</template>

<style scoped lang="scss">
.block--image {
    width: 100%;

    img {
        border-radius: 1.6rem;
        //padding: 0.2rem;
        //background: white;
        //border: 0.3rem solid var(--color-dark-primary);
        //box-shadow: -0.6rem 0.6rem 0 rgb(0 0 0 / 0.25);
    }
}
</style>
