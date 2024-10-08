<script setup>
import DeleteButton from "./DeleteButton.vue";
import {onMounted, ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

const props = defineProps({
    modelType: String,
    routes: Object,
    isUser: Boolean,
    isAuthor: Boolean,
    isPinned: Boolean,
});

const isOpen = ref(false);
const reference = ref(null);
const floating = ref(null);
const {floatingStyles} = useFloating(reference, floating, {
    placement: 'right',
    middleware: [offset(8), flip(), shift()]
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

onMounted(() => {
    reference.value.addEventListener('mouseenter', () => {
        isOpen.value = true;
    });
    reference.value.addEventListener('mouseleave', () => {
        isOpen.value = false;
    });
});

</script>

<template>
    <a :href="routes.view">View Deck</a>

    <template v-if="isAuthor">
        <a :href="routes.edit">Edit Deck</a>
        <DeleteButton :modelType="modelType" :route="routes.delete"/>
    </template>

    <a ref="reference" :href="routes.study">Study Deck</a>

    <template v-if="isUser">
        <a :href="routes.creator">View Creator</a>
        <form :action="routes.copy" method="POST" @submit="confirmCopy">
            <input type="hidden" name="_token" :value="csrfToken">
            <button type="submit">Copy Deck</button>
        </form>
        <a href="#" @click="copyLink">Share Link</a>
        <form :action="routes.export" method="POST">
            <input type="hidden" name="_token" :value="csrfToken">
            <button type="submit">Export CSV</button>
        </form>
    </template>
</template>
