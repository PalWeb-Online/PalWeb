<script setup>
import Layout from "../Shared/Layout.vue";
import {computed, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useConnectionStatus} from "../composables/useConnectionStatus.js";

const props = defineProps({status: Number})

const {browserOnline} = useConnectionStatus(Echo);

const title = computed(() => {
    return {
        503: 'Unavailable',
        500: 'Server Error',
        404: 'Not Found',
        403: 'Forbidden!',
        911: 'Offline'
    }[props.status]
})

const message = computed(() => {
    return {
        503: 'We\'re doing some maintenance. Check back soon.',
        500: 'Oops! Something went wrong.',
        404: 'What you\'re looking for doesn\'t exist.',
        403: 'Whatever you just did — don\'t.',
        911: 'Womp womp. Try again later.',
    }[props.status]
})

watch(browserOnline, (isOnline) => {
    if (props.status !== 911) {
        return;
    }

    if (isOnline) {
        router.visit(route('homepage'), {
            replace: true,
            preserveScroll: true,
        });
    }
}, {immediate: true});

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

<style scoped lang="scss">
.error-container {
    display: grid;
    gap: 1.6rem;
    text-align: center;
    margin-block: 9.6rem;

    .error-status {
        font-family: var(--display-font);
        font-size: 12.8rem;
        color: var(--color-dark-primary);
        line-height: 0.75;
        padding-block-end: 1.6rem;
    }

    .error-title {
        font-family: var(--head-font);
        font-size: 6.4rem;
        font-weight: 700;
        color: var(--color-medium-primary);
        padding-block-start: 0.8rem;
    }

    .error-message {
        font-family: var(--mono-font);
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--color-dark-primary);
    }
}
</style>
