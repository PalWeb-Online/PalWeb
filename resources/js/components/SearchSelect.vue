<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";

const props = defineProps({
    modelValue: {
        type: [Number, String, null],
        default: null,
    },
    label: {
        type: String,
        required: true,
    },
    initialTitle: {
        type: String,
        default: "",
    },
    search: {
        type: Function,
        required: true,
    },
    error: {
        type: [String, Array, null],
        default: null,
    },
    minimumCharacters: {
        type: Number,
        default: 0,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue", "select", "clear"]);

const root = ref(null);
const searchTerm = ref("");
const selectedTitle = ref(props.initialTitle);
const isOpen = ref(false);
const isLoading = ref(false);
const options = ref([]);

let debounceTimeout = null;

const normalizedError = computed(() => {
    if (Array.isArray(props.error)) {
        return props.error[0] ?? null;
    }

    return props.error;
});

const displayValue = computed({
    get() {
        return isOpen.value ? searchTerm.value : selectedTitle.value;
    },
    set(value) {
        searchTerm.value = value;
    },
});

watch(
    () => props.initialTitle,
    (value) => {
        selectedTitle.value = value || "";
    }
);

watch(
    () => props.modelValue,
    (value) => {
        if (!value) {
            selectedTitle.value = "";
        }
    }
);

watch(searchTerm, (value) => {
    if (props.disabled) {
        return;
    }

    if (!isOpen.value) {
        isOpen.value = true;
    }

    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }

    debounceTimeout = setTimeout(() => {
        fetchOptions(value);
    }, 300);
});

const fetchOptions = async (q = "") => {
    if (props.disabled) {
        options.value = [];
        return;
    }

    if ((q || "").length < props.minimumCharacters) {
        options.value = [];
        return;
    }

    isLoading.value = true;

    try {
        options.value = await props.search(q || "");
    } catch (e) {
        console.error("Failed to fetch search results", e);
        options.value = [];
    } finally {
        isLoading.value = false;
    }
};

const openDropdown = () => {
    if (props.disabled) {
        return;
    }

    isOpen.value = true;

    if (options.value.length === 0) {
        fetchOptions(searchTerm.value);
    }
};

const selectOption = (option) => {
    selectedTitle.value = option.title;
    searchTerm.value = "";
    emit("update:modelValue", option.id);
    emit("select", option);
    isOpen.value = false;
};

const clearSelection = () => {
    if (props.disabled) {
        return;
    }

    selectedTitle.value = "";
    searchTerm.value = "";
    options.value = [];
    emit("update:modelValue", null);
    emit("clear");
    isOpen.value = true;
    fetchOptions('');
};

const handleClickOutside = (event) => {
    if (!isOpen.value) {
        return;
    }

    const el = root.value;

    if (el && !el.contains(event.target)) {
        isOpen.value = false;
        searchTerm.value = "";
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);

    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }
});
</script>

<template>
    <div class="field-item search-select" ref="root">
        <label>{{ label }}</label>

        <div class="search-select-bar">
            <input
                type="text"
                :class="{ persisting: modelValue }"
                placeholder="Search"
                :disabled="disabled"
                v-model="displayValue"
                @focus="openDropdown"
                @click="openDropdown"
            >

            <button
                v-if="modelValue && !disabled"
                type="button"
                class="material-symbols-rounded search-select-clear"
                @click.prevent="clearSelection"
            >
                delete
            </button>
        </div>

        <div v-if="normalizedError" v-text="normalizedError" class="field-error"/>

        <div v-if="isOpen && !disabled" class="search-select-dropdown">
            <ul class="search-select-list">
                <li v-if="isLoading" class="search-select-item status">
                    Searching...
                </li>

                <li v-else-if="options.length === 0" class="search-select-item status">
                    No results.
                </li>

                <li
                    v-else
                    v-for="option in options"
                    :key="option.id"
                    class="search-select-item"
                    @mousedown.prevent="selectOption(option)"
                >
                    <slot name="option" :option="option">
                        {{ option.title }}
                    </slot>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped lang="scss">
.search-select {
    position: relative;
}

.search-select-bar {
    position: relative;
    display: flex;
    align-items: center;

    input {
        width: 100%;

        &.persisting {
            font-weight: 700;
            background: var(--color-pastel-medium);
        }
    }

    .search-select-clear {
        position: absolute;
        right: 0.8rem;
        cursor: pointer;
        font-size: 1.8rem;
        color: var(--color-medium-primary);
    }
}

.search-select-dropdown {
    position: absolute;
    z-index: 1;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--color-pastel-light);
    border: 0.1rem solid var(--color-pastel-dark);
    max-height: 16rem;
    overflow-y: auto;
    margin: 0.6rem 0.4rem 0;
    border-radius: 0.8rem;
    font-size: 1.2rem;
    font-family: var(--mono-font), monospace;

    .search-select-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .search-select-item {
        padding: 0.8rem 1.2rem;

        &:not(.status) {
            cursor: pointer;

            &:hover {
                background: var(--color-pastel-medium);
            }
        }
    }
}
</style>
