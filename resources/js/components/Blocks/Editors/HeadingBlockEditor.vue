<script setup>
const props = defineProps({
    block: {type: Object, required: true},
});

const headingLevels = ['h1', 'h2', 'h3'];

if (!headingLevels.includes(props.block.level)) {
    props.block.level = 'h3';
}
</script>

<template>
    <div class="block-editor--heading">
        <select v-model="props.block.level" :class="{ 'invalid': !headingLevels.includes(props.block.level) }">
            <option v-for="level in headingLevels" :key="level" :value="level">
                {{ level }}
            </option>
        </select>
        <div class="field-item">
            <input v-model="props.block.title" placeholder="Title"
                   :class="{ 'invalid': !props.block.title?.trim() }"
            />
        </div>
    </div>
</template>

<style scoped lang="scss">
.block-editor--heading {
    display: flex;
    gap: 1.6rem;
    align-items: center;
}
</style>
