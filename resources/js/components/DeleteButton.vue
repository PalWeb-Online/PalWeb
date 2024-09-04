<script setup>
import {computed} from "vue";

const props = defineProps({
    modelType: String,
    route: String,
});

const model = computed(() => {
    return props.modelType ? props.modelType.charAt(0).toUpperCase() + props.modelType.slice(1) : '';
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmDelete = (event) => {
    if (!confirm(`Are you sure you want to delete this ${model.value}?`)) {
        event.preventDefault();
    }
};

</script>

<template>
    <form :action="route" method="POST" @submit="confirmDelete">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" :value="csrfToken">
        <button type="submit">Delete {{ model }}</button>
    </form>
</template>
