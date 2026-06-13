<script setup>
import {reactive, watch} from "vue";
import WikiNavItem from "./WikiNavItem.vue";
import {useUserStore} from "../../../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    pageTree: {
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

const findPagePath = (pageTree, slug, path = []) => {
    for (const page of pageTree) {
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

    const currentPath = findPagePath(props.pageTree, props.currentSlug) ?? [];

    currentPath.forEach((page) => {
        if (hasChildren(page)) {
            openSections[page.slug] = true;
        }
    });
};

watch(
    () => [props.pageTree, props.currentSlug],
    resetOpenSections,
    {immediate: true, deep: true}
);
</script>

<template>
    <div class="wiki-nav">
        <template v-for="page in pageTree" :key="page.slug">
            <WikiNavItem
                v-if="page.status === 'published' || UserStore.isAdmin"
                :page="page"
                :current-slug="currentSlug"
                :open-sections="openSections"
            />
        </template>
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
