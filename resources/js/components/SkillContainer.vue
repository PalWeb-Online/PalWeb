<script setup>
import {ref} from "vue";

defineProps({
    type: String,
    title: String,
})

const isOpen = ref(false);
</script>
<template>
    <div class="lesson-concept-container skill">
        <div class="lesson-concept-head" @click="isOpen = !isOpen">
            <div class="featured-title s">skill: {{ type }}</div>
        </div>

        <div v-if="isOpen" class="lesson-concept-body">
            <h1>{{ title }}</h1>
            <slot/>
        </div>
    </div>
</template>
