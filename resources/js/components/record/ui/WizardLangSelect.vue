<script setup>
import {onMounted, ref, watch} from 'vue';
import Multiselect from 'vue-multiselect'; // Assuming you're using vue-multiselect
import axios from 'axios';

// Example options for dialects (replace with actual data from the database)
const dialectOptions = ref([
    {id: 1, name: 'Palestinian Arabic - Gaza', proficiency: '', location: ''},
    {id: 2, name: 'Palestinian Arabic - West Bank', proficiency: '', location: ''},
    {id: 3, name: 'Palestinian Arabic - Jerusalem', proficiency: '', location: ''}
]);

// Array to store the selected dialects
const selectedDialects = ref([]);

// Watch for changes to selected dialects and handle updates accordingly
watch(selectedDialects, (newValue, oldValue) => {
    console.log('Selected dialects updated:', newValue);
});

// Method to remove a dialect from the list
const removeDialect = (index) => {
    selectedDialects.value.splice(index, 1);
};

onMounted(async () => {
    try {
        const response = await axios.get('/api/dialects'); // Fetch dialects from the API
        dialectOptions.value = response.data;
    } catch (error) {
        console.error('Error fetching dialects:', error);
    }
});
</script>

<template>
    <div>
        <!-- Multi-select dropdown for selecting dialects -->
        <multiselect
            v-model="selectedDialects"
            :options="dialectOptions"
            multiple
            label="name"
            track-by="id"
            placeholder="Select dialects"
        >
        </multiselect>

        <!-- Display additional metadata (proficiency and location) for each selected dialect -->
        <div v-for="(dialect, index) in selectedDialects" :key="index">
            <h4>{{ dialect.name }}</h4>

            <!-- Proficiency Level Selector -->
            <label>Proficiency</label>
            <select v-model="dialect.proficiency">
                <option value="native">Native</option>
                <option value="fluent">Fluent</option>
                <option value="intermediate">Intermediate</option>
                <option value="beginner">Beginner</option>
            </select>

            <!-- Location Input -->
            <label>Location</label>
            <input v-model="dialect.location" placeholder="Enter location"/>

            <!-- Button to remove the dialect -->
            <button @click="removeDialect(index)">Remove</button>
        </div>
    </div>
</template>

<style scoped>
/* Style the component */
h4 {
    margin-top: 10px;
}

button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    margin-top: 5px;
}

button:hover {
    background-color: darkred;
}

input {
    display: block;
    margin-top: 5px;
}

select {
    display: block;
    margin-top: 5px;
}
</style>
