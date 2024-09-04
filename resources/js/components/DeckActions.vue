<script setup>
import DeleteButton from "./DeleteButton.vue";

const props = defineProps({
    modelType: String,
    routes: Object,
    isUser: Boolean,
    isAuthor: Boolean,
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmCopy = (event) => {
    if (!confirm('Are you sure you want to create a copy of this Deck?')) {
        event.preventDefault();
    }
};

const copyLink = (event) => {
    event.preventDefault();

    navigator.clipboard.writeText(props.routes.view).then(function () {
        alert('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
};

</script>

<template>
    <a :href="routes.view">View Deck</a>

    <template v-if="isUser">
        <form :action="routes.copy" method="POST" @submit="confirmCopy">
            <input type="hidden" name="_token" :value="csrfToken">
            <button type="submit">Copy Deck</button>
        </form>
        <a :href="routes.creator">View Creator</a>

        <template v-if="isAuthor">
            <div class="action-divider"></div>
            <a :href="routes.edit">Edit Deck</a>
            <DeleteButton :modelType="modelType" :route="routes.delete"/>
        </template>

        <div class="action-divider"></div>
        <a href="#" @click="copyLink">Share Link</a>
        <form :action="routes.export" method="POST">
            <input type="hidden" name="_token" :value="csrfToken">
            <button type="submit">Export to CSV</button>
        </form>
    </template>
</template>
