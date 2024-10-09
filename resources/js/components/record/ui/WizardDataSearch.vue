<script setup>
import {ref, watch} from 'vue';
import axios from 'axios'; // Replace with fetch if needed

const props = defineProps({
    value: String,
    placeholder: String,
    disabled: {type: Boolean, default: false},
    inputId: String,
});

const emit = defineEmits(['input', 'change']);

const searchQuery = ref('');
const searchResults = ref([]);
const isDropdownVisible = ref(false);

const onSearch = async () => {
    if (!searchQuery.value) {
        searchResults.value = [];
        isDropdownVisible.value = false;
        return;
    }

    // Make an API request to search your database based on the searchQuery
    try {
        const response = await axios.get(`/api/search/locations`, {
            params: {query: searchQuery.value},
        });

        searchResults.value = response.data;
        isDropdownVisible.value = searchResults.value.length > 0;
    } catch (error) {
        console.error('Error fetching search results:', error);
    }
};

const selectItem = (item) => {
    searchQuery.value = item.label;
    isDropdownVisible.value = false;

    // Emit the selected item’s id (or another field) to the parent component
    emit('input', item.id);
    emit('change', item);
};

// Watch for external changes to the value prop (in case of resets)
watch(
    () => props.value,
    (newValue) => {
        // If the prop changes, set the corresponding query value
        searchQuery.value = newValue;
    }
);
</script>

<template>
    <div>
        <input
            v-model="searchQuery"
            :placeholder="placeholder"
            @input="onSearch"
            :disabled="disabled"
            :id="inputId"
            class="data-search-input"
        />
        <ul v-if="isDropdownVisible" class="data-search-dropdown">
            <li v-for="item in searchResults" :key="item.id" @click="selectItem(item)">
                {{ item.label }} <small v-if="item.description">{{ item.description }}</small>
            </li>
        </ul>
    </div>
</template>

<style scoped>
.data-search-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.data-search-dropdown {
    background: white;
    border: 1px solid #ccc;
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    width: 100%;
    z-index: 1000;
}

.data-search-dropdown li {
    padding: 8px;
    cursor: pointer;
}

.data-search-dropdown li:hover {
    background-color: #f0f0f0;
}
</style>
