<script setup>
import {computed, ref, watch} from "vue";
import Draggable from "vuedraggable";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";

const CURRENT_PAGE_KEY = "current-page-position";

const props = defineProps({
    modelValue: {type: Boolean, default: false},
    pageTree: {type: Array, default: () => []},
    parentId: {type: [Number, String, null], default: null},
    currentPageId: {type: [Number, String, null], default: null},
    currentTitle: {type: String, default: ""},
    position: {type: [Number, String, null], default: 1},
    isLoading: {type: Boolean, default: false},
});

const emit = defineEmits(["update:modelValue", "select"]);

const orderedPages = ref([]);

const modalOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const normalizedParentId = computed(() => normalizeId(props.parentId));
const normalizedCurrentPageId = computed(() => normalizeId(props.currentPageId));

const placeholder = computed(() => ({
    id: CURRENT_PAGE_KEY,
    positionKey: CURRENT_PAGE_KEY,
    title: props.currentTitle?.trim() || "This Page",
    slug: null,
    position: props.position || 1,
    parent_id: normalizedParentId.value,
    isCurrent: true,
}));

const normalizeId = (value) => {
    if (value === null || value === undefined || value === "") return null;

    return Number(value);
};

const findPage = (pages, id) => {
    if (!id) return null;

    for (const page of pages) {
        if (Number(page.id) === id) return page;

        const childMatch = findPage(page.children ?? [], id);
        if (childMatch) return childMatch;
    }

    return null;
};

const siblingPages = computed(() => {
    if (!normalizedParentId.value) {
        return props.pageTree ?? [];
    }

    return findPage(props.pageTree ?? [], normalizedParentId.value)?.children ?? [];
});

const parentTitle = computed(() => {
    if (!normalizedParentId.value) return 'Root';

    return findPage(props.pageTree ?? [], normalizedParentId.value)?.title ?? 'Selected Parent';
});

const buildOrderedPages = () => {
    const siblings = siblingPages.value
        .filter((page) => Number(page.id) !== normalizedCurrentPageId.value)
        .map((page) => ({
            ...page,
            positionKey: page.id,
            isCurrent: false,
        }));

    const insertIndex = Math.min(
        Math.max(Number(props.position || 1) - 1, 0),
        siblings.length,
    );

    siblings.splice(insertIndex, 0, placeholder.value);
    orderedPages.value = siblings;
};

const selectPosition = () => {
    const index = orderedPages.value.findIndex((page) => page.isCurrent);

    if (index === -1) return;

    emit("select", index + 1);
    modalOpen.value = false;
};

watch(
    () => [
        props.modelValue,
        props.pageTree,
        props.parentId,
        props.currentPageId,
        props.currentTitle,
        props.position,
    ],
    () => {
        if (props.modelValue) {
            buildOrderedPages();
        }
    },
    {deep: true, immediate: true},
);
</script>

<template>
    <div class="window-container modal-container page-position-modal">
        <div class="window-section-head">
            <h1>page position</h1>
        </div>

        <AppTip>
            <p>Positioning the <b>{{ currentTitle }}</b> Page among the pages in the <b>{{ parentTitle }}</b> node. If
                you would like to change the nesting of the Page, select a different Parent first.</p>
        </AppTip>

        <div class="modal-container-body form-body">
            <LoadingSpinner v-if="isLoading"/>
            <Draggable
                v-else
                class="page-position-list"
                :list="orderedPages"
                item-key="positionKey"
                handle=".handle"
            >
                <template #item="{ element, index }">
                    <li
                        class="draggable-item page-position-item"
                        :class="{ current: element.isCurrent }"
                    >
                        <div class="page-position-number">{{ index + 1 }}</div>
                        <div class="page-position-label">
                            <strong>{{ element.title }}</strong>
                            <span>/{{ element.slug ?? '{page}' }}</span>
                        </div>
                        <span v-if="element.isCurrent" class="handle material-symbols-rounded">drag_indicator</span>
                    </li>
                </template>
            </Draggable>
        </div>

        <div class="window-footer">
            <button type="button" @click="selectPosition">
                Select
            </button>
            <button type="button" @click="modalOpen = false">
                Cancel
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.page-position-modal {
    .modal-container-body {
        gap: 1.6rem;
    }
}

.page-position-list {
    display: grid;
    gap: 0.8rem;
    padding: 0;
    margin: 0;
}

.page-position-item {
    display: grid;
    grid-template-columns: min-content 1fr 1.6rem;
    gap: 0.8rem;
    padding: 0;
    color: var(--color-dark-primary);

    &.current {
        .page-position-number {
            background: var(--color-accent-medium);
            color: var(--color-dark-primary);
        }

        .page-position-label {
            background: var(--color-accent-light);
        }
    }

    .material-symbols-rounded {
        align-self: center;
        justify-self: center;
        color: inherit;
    }
}

.page-position-number {
    display: grid;
    place-items: center;
    min-width: 3.6rem;
    min-height: 3.6rem;
    border-radius: 50%;
    background: var(--color-dark-primary);
    color: white;
    font-family: var(--mono-font);
    font-weight: 700;
}

.page-position-label {
    display: grid;
    align-content: center;
    background: var(--color-pastel-light);
    padding: 0.8rem 1.6rem;
    border-radius: 0.6rem;
    min-width: 0;

    strong,
    span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    span {
        font-family: var(--mono-font);
        font-size: 1.2rem;
        opacity: 0.8;
    }
}
</style>
