<script setup>
defineProps({
    items: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <ul>
        <template v-for="item in items" :key="item.id">
            <li>
                <a :href="`#${item.id}`">
                    {{ item.title }}
                </a>
            </li>
            <PageContents
                v-if="item.children.length"
                :items="item.children"
            />
        </template>
    </ul>
</template>

<style scoped lang="scss">
ul {
    list-style: disc;
    padding-inline-start: 3.2rem;
    color: var(--color-medium-primary);
}
</style>
