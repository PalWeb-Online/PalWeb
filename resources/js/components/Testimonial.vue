<script setup>
import UserItem from "./UserItem.vue";

const props = defineProps({
    testimonial: Object
});
</script>
<template>
    <div class="testimonial-container">
        <UserItem :user="testimonial.user" size="l" comment>
            <template #comment>
                {{ testimonial.comment }}
            </template>
        </UserItem>
    </div>
</template>

<style scoped>
.testimonial-container {
    width: min(80vw, 72rem);
    background: white;
    padding: 3.2rem;
    border-radius: 1.6rem
}
</style>
