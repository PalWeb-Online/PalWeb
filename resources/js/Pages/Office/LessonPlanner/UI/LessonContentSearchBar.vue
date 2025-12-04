<script setup>
import {ref, watch, computed, onMounted, onBeforeUnmount} from "vue";
import axios from "axios";
import {route} from "ziggy-js";

const props = defineProps({
    modelValue: {
        type: [Number, String, null],
        default: null,
    },
    type: {
        type: String,
        required: true,
    },
    lessonId: {
        type: [Number, String, null],
        default: null,
    },
    initialTitle: {
        type: String,
        default: "",
    }
});

const emit = defineEmits(["update:modelValue"]);

const root = ref(null);
const searchTerm = ref("");
const isOpen = ref(false);
const isLoading = ref(false);
const options = ref([]);
const selectedTitle = ref(props.initialTitle);

let debounceTimeout = null;

const displayValue = computed({
    get() {
        return isOpen.value ? searchTerm.value : selectedTitle.value;
    },
    set(value) {
        searchTerm.value = value;
    }
});

watch(
    () => searchTerm.value,
    (newValue) => {
        if (!isOpen.value) {
            isOpen.value = true;
        }

        if (debounceTimeout) {
            clearTimeout(debounceTimeout);
        }

        debounceTimeout = setTimeout(() => {
            fetchOptions(newValue);
        }, 300);
    }
);

const fetchOptions = async (q) => {
    if (!props.type) {
        return;
    }

    isLoading.value = true;

    try {
        const response = await axios.get(route('lessons.search'), {
            params: {
                type: props.type,
                q: q || "",
                lesson_id: props.lessonId || null,
            },
        });

        options.value = response.data.data || [];
    } catch (e) {
        console.error("Failed to fetch search results", e);
        options.value = [];
    } finally {
        isLoading.value = false;
    }
};

const selectOption = (option) => {
    selectedTitle.value = option.title;
    emit("update:modelValue", option.id);
    isOpen.value = false;
};

const clearSelection = () => {
    selectedTitle.value = "";
    emit("update:modelValue", null);
    isOpen.value = true;
};

const openDropdown = () => {
    if (props.type === 'unit') {
        isOpen.value = true;
        fetchOptions();
    }
}

const handleClickOutside = (event) => {
    if (!isOpen.value) {
        return;
    }

    const el = root.value;
    if (el && !el.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div class="field-item search-select" ref="root">
        <label>
            {{ type }}
        </label>

        <div class="search-select-bar">
            <input
                type="text"
                :class="{'persisting': modelValue}"
                v-model="displayValue"
                @click="openDropdown"
            />

            <div
                v-if="modelValue"
                type="button"
                class="material-symbols-rounded search-select-clear"
                @click.prevent="clearSelection"
            >
                delete
            </div>
        </div>

        <div v-if="isOpen" class="search-select-dropdown">
            <ul class="search-select-list">
                <li v-if="isLoading" class="search-select-item status">Searching...</li>
                <li v-else-if="options.length === 0" class="search-select-item status">No results.</li>
                <li v-else
                    v-for="option in options"
                    :key="option.id"
                    class="search-select-item"
                    @mousedown.prevent="selectOption(option)"
                >
                    {{ option.title }}
                </li>
            </ul>
        </div>
    </div>
</template>
