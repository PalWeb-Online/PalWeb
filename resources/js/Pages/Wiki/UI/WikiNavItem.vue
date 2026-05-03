<script setup>
import {route} from "ziggy-js";

const props = defineProps({
    page: {
        type: Object,
        required: true,
    },
    currentSlug: {
        type: String,
        required: true,
    },
    openSections: {
        type: Object,
        required: true,
    },
    depth: {
        type: Number,
        default: 0,
    },
});

const hasChildren = (page) => {
    return Array.isArray(page.children) && page.children.length > 0;
};

const isSectionOpen = (slug) => {
    return props.openSections[slug] ?? false;
};

const toggleSection = (slug) => {
    props.openSections[slug] = !isSectionOpen(slug);
};
</script>

<template>
    <div class="wiki-nav-section">
        <div
            class="wiki-nav-section-head"
            :class="{ active: currentSlug === page.slug }"
            :style="{ '--wiki-nav-depth': depth }"
        >
            <Link
                :href="route('wiki.show', page.slug)"
                preserve-state
            >
                {{ page.title }}
            </Link>

            <button
                v-if="hasChildren(page)"
                type="button"
                class="material-symbols-rounded"
                @click="toggleSection(page.slug)"
            >
                {{ isSectionOpen(page.slug) ? 'expand_less' : 'expand_more' }}
            </button>
        </div>

        <div
            v-if="hasChildren(page) && isSectionOpen(page.slug)"
            class="wiki-nav-subpages"
        >
            <WikiNavItem
                v-for="child in page.children"
                :key="child.slug"
                :page="child"
                :current-slug="currentSlug"
                :open-sections="openSections"
                :depth="depth + 1"
            />
        </div>
    </div>
</template>

<style scoped lang="scss">
.wiki-nav > .wiki-nav-section:not(:last-child) {
    border-block-end: 0.1rem solid var(--color-dark-primary);
}

.wiki-nav-section {
    display: grid;
    background: var(--color-medium-primary);
}


.wiki-nav-subpages > .wiki-nav-section {
    background: var(--color-pastel-medium);

    .wiki-nav-subpages .wiki-nav-section-head {
        a, button {
            background: var(--color-pastel-light);
        }

        &.active a, &.active button {
            color: white;
            background: var(--color-medium-secondary);
        }
    }
}

.wiki-nav-section-head {
    display: flex;
    font-size: 1.6rem;
    font-weight: 500;
    height: 3.6rem;

    &.active {
        color: white;
        background: var(--color-medium-secondary);
    }

    a {
        display: flex;
        align-items: center;
        flex-grow: 1;
        font-weight: 700;
        padding-inline-start: calc(1.6rem + (var(--wiki-nav-depth) * 0.8rem));
        padding-inline-end: 1.6rem;
    }

    a, button {
        color: white;
    }

    button {
        width: 3.6rem;
    }
}

.wiki-nav-subpages {
    display: grid;

    a {
        font-size: 1.4rem;
        font-weight: 400;
    }

    a, button {
        color: var(--color-dark-primary);
    }

    .wiki-nav-section-head {
        &.active a, &.active button {
            color: white;
            background: var(--color-medium-secondary);
        }
    }
}
</style>
