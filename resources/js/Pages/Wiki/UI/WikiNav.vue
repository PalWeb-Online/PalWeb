<script setup>
import {reactive, watch} from "vue";
import WikiNavItem from "./WikiNavItem.vue";

const props = defineProps({
    pages: {
        type: Array,
        default: () => [],
    },
    currentSlug: {
        type: String,
        default: 'about',
    },
});

const openSections = reactive({});

const hasChildren = (page) => {
    return Array.isArray(page.children) && page.children.length > 0;
};

const findPagePath = (pages, slug, path = []) => {
    for (const page of pages) {
        const nextPath = [...path, page];

        if (page.slug === slug) {
            return nextPath;
        }

        if (hasChildren(page)) {
            const childPath = findPagePath(page.children, slug, nextPath);

            if (childPath) {
                return childPath;
            }
        }
    }

    return null;
};

const resetOpenSections = () => {
    Object.keys(openSections).forEach((slug) => {
        delete openSections[slug];
    });

    const currentPath = findPagePath(props.pages, props.currentSlug) ?? [];

    currentPath.forEach((page) => {
        if (hasChildren(page)) {
            openSections[page.slug] = true;
        }
    });
};

watch(
    () => [props.pages, props.currentSlug],
    resetOpenSections,
    {immediate: true, deep: true}
);
</script>

<template>
    <div class="wiki-nav">
        <WikiNavItem
            v-for="page in pages"
            :key="page.slug"
            :page="page"
            :current-slug="currentSlug"
            :open-sections="openSections"
        />
    </div>
</template>

<style scoped lang="scss">
.wiki-nav {
    flex: 1 0 24rem;
    align-content: start;
    background: var(--color-pastel-light);
    font-weight: 700;
    font-size: 1.6rem;
    user-select: none;
    white-space: nowrap;
    text-transform: capitalize;
}
</style>
