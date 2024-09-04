<script setup>

const props = defineProps({
  deleteRoute: String,
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmDelete = (event) => {
  if (!confirm('Are you sure you want to delete this term?')) {
    event.preventDefault();
  }
};

</script>

<template>
  <form :action="deleteRoute" method="POST" @submit="confirmDelete">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="_token" :value="csrfToken">
    <button type="submit">Delete Term</button>
  </form>
</template>
