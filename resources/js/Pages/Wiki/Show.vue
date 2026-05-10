<script setup>
import WikiNav from "./UI/WikiNav.vue";
import AppTip from "../../components/AppTip.vue";
import {computed, onMounted, ref, watch} from "vue";
import {route} from "ziggy-js";
import Layout from "../../Shared/Layout.vue";
import LoadingSpinner from "../../Shared/LoadingSpinner.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {getHeadingAnchorId} from "../../components/Blocks/headingAnchors.js";
import PageContents from "./UI/PageContents.vue";
import DocumentBlocksRenderer from "../../components/Blocks/Renderers/DocumentBlocksRenderer.vue";
import {usePageViewer} from "../../composables/pages/usePageViewer.js";

defineOptions({
    layout: Layout,
});

const props = defineProps({
    pageId: {
        type: Number,
        required: false,
    },
});

const UserStore = useUserStore();
const navOpen = ref(false);

const {
    page,
    pageNotFound,
    isLoadingPage,
    pageTree,
    isLoadingTree,
    fetchWikiTree,
    loadPage,
    reloadPage,
} = usePageViewer();

const headingLevelNumber = (level) => {
    return {
        h1: 1,
        h2: 2,
        h3: 3,
    }[level] ?? 2;
};

const collectHeadingBlocks = (blocks = []) => {
    return blocks.flatMap((block) => {
        if (block?.type === 'heading') {
            return [block];
        }

        if (block?.type === 'container') {
            return collectHeadingBlocks(block.blocks ?? []);
        }

        return [];
    });
};

const tableOfContents = computed(() => {
    const headings = collectHeadingBlocks(page.value?.document?.blocks ?? [])
        .filter((block) => block.title?.trim())
        .filter((block) => ['h1', 'h2', 'h3'].includes(block.level))
        .map((block) => ({
            id: getHeadingAnchorId(block),
            title: block.title,
            level: block.level,
            levelNumber: headingLevelNumber(block.level),
            children: [],
        }));

    const root = [];
    const stack = [{levelNumber: 0, children: root}];

    headings.forEach((heading) => {
        while (stack.length > 1 && heading.levelNumber <= stack[stack.length - 1].levelNumber) {
            stack.pop();
        }

        stack[stack.length - 1].children.push(heading);
        stack.push(heading);
    });

    return root;
});

onMounted(async () => {
    // todo: is the Wiki tree blocking render? what about loading the page?
    await Promise.all([
        fetchWikiTree(),
        loadPage(props.pageId),
    ]);
});

watch(() => props.pageId, async () => {
    await reloadPage(props.pageId)
});
</script>

<template>
    <Head :title="page ? `Wiki: ${page?.title}` : 'Wiki'"/>

    <div id="app-head">
        <h1>wiki</h1>
    </div>

    <div class="wiki-container">
        <div class="window-section-head">
            <button
                @click="navOpen = !navOpen"
                class="material-symbols-rounded menu"
                :class="{ active: navOpen }"
            >
                menu
            </button>

            <h1>{{ page?.title }}</h1>
            <Link :href="route('wiki.edit', page)" class="material-symbols-rounded">edit</Link>
            <Link :href="route('wiki.edit')" class="material-symbols-rounded">add</Link>
        </div>

        <AppTip dismissable>
            <p>The <b>Wiki</b> is an extensive repository of information about Palestinian Arabic & PalWeb. Due to time
                constraints, these pages are not updated very often, especially those that pertain to Palestinian Arabic
                as such. Be aware that some of this information may be outdated or incomplete. If you are interested in
                writing or co-writing about Palestinian Arabic for PalWeb, send an email to <b>adrian@palweb.app</b>.
            </p>
        </AppTip>

        <div class="wiki-body" :class="{ 'nav-open': navOpen }">
            <WikiNav v-if="!isLoadingTree"
                     :page-tree="pageTree"
                     :current-slug="page?.slug"
            />

            <LoadingSpinner v-if="isLoadingPage"/>
            <div v-else-if="pageNotFound" class="wiki-blocks-wrapper">
                <p>Sorry, but the requested page does not exist.</p>

                <p v-if="UserStore.isAdmin">
                    <Link :href="route('wiki.edit', page)">Create this Page</Link>
                </p>

                <p>
                    <Link :href="route('wiki.show')">Go to Wiki Home</Link>
                </p>
            </div>
            <div v-else-if="page" class="wiki-blocks-wrapper">
                <div class="featured-title l" style="z-index: 1">{{ page.title }}</div>

                <nav v-if="tableOfContents.length" class="wiki-toc" aria-label="Table of Contents">
                    <PageContents :items="tableOfContents"/>
                </nav>

                <DocumentBlocksRenderer :blocks="page.document?.blocks ?? []"/>
            </div>
        </div>
    </div>
</template>
<style scoped lang="scss">
.wiki-body {
    display: flex;
    background: white;

    .wiki-nav {
        display: none;
    }

    &.nav-open {
        .wiki-nav {
            display: grid;
        }

        .wiki-blocks-wrapper {
            display: none;
        }

        @media (min-width: 960px) {
            .wiki-nav {
                flex-grow: 0;
                max-width: 25%;
            }

            .wiki-blocks-wrapper {
                display: grid;
            }
        }
    }
}

.wiki-container {
    width: 100%;
    max-width: 128rem;
    justify-self: center;

    @media (min-width: 1280px) {
        margin-block-end: 9.6rem;
        overflow: hidden;
    }

    .window-section-head .menu.active {
        background: var(--color-accent-medium);
    }

    & > .app-tip {
        width: 100%;
        max-width: none;
        border-radius: 0;
        border: none;
    }
}

.wiki-toc {
    min-width: 50%;
    display: grid;
    justify-self: start;
    gap: 0.8rem;
    padding: 3.2rem 3.2rem 1.6rem 1.6rem;
    background: var(--color-pastel-light);
    margin-block: -4.8rem 3.2rem;
    margin-inline: 1.6rem;
}

.wiki-blocks-wrapper {
    flex-grow: 1;
    display: grid;
    align-content: start;
    gap: 2.4rem;
    padding: 4.8rem 4.8rem 6.4rem;
    background: white;

    &:last-child {
        margin-block-end: 9.6rem;
    }

    @media (min-width: 1280px) {
        &:last-child {
            margin-block-end: 0;
        }
    }

    .app-tip {
        margin-block: 3.2rem;
    }

    & > *:first-child {
        margin-block-start: 0;
    }

    ol {
        padding-inline-end: 0.8rem;
    }

    a {
        color: var(--color-dark-primary);
        font-weight: 700;

        &:hover {
            text-decoration: underline;
        }
    }
}
</style>
