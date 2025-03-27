<script setup>
import Layout from "../Shared/Layout.vue";
import {computed} from "vue";

const props = defineProps({status: Number})

const title = computed(() => {
    return {
        503: 'Unavailable',
        500: 'Server Error',
        404: 'Not Found',
        403: 'Forbidden!',
    }[props.status]
})

const message = computed(() => {
    return {
        503: 'We\'re doing some maintenance. Check back soon.',
        500: 'Oops! Something went wrong.',
        404: 'What you\'re looking for doesn\'t exist.',
        403: 'Whatever you just did — don\'t.',
    }[props.status]
})

defineOptions({
    layout: Layout
});
</script>
<template>
    <Head title="Error"/>
    <div id="app-body">
        <div class="error-container">
            <div class="error-status">{{ status }}</div>
            <div class="error-title">{{ title }}</div>
            <div class="error-message">{{ message }}</div>
        </div>
    </div>
</template>
