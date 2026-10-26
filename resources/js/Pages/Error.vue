<script setup>
import {watch} from "vue";
import {useRouter} from "vue-router";
import {useConnectionStatus} from "../composables/useConnectionStatus.js";
import ZatuneThinking from '../Shared/ZatuneThinking.vue';

const props = defineProps({status: Number})
const router = useRouter();

const {browserOnline} = useConnectionStatus(Echo);

watch(browserOnline, (isOnline) => {
    if (props.status !== 911) {
        return;
    }

    if (isOnline) {
        router.replace({name: 'homepage'});
    }
}, {immediate: true});
</script>
<template>
    <div id="app-body">
        <div class="error-container">
            <div class="error-status">{{ status }}</div>
            <div class="error-title">{{ $t(`pages.error.${status}.title`) }}</div>
            <div class="error-message">{{ $t(`pages.error.${status}.message`) }}</div>
            <ZatuneThinking/>
        </div>
    </div>
</template>

<style scoped lang="scss">
svg {
    width: 32rem;
    margin-block-start: 6.4rem;
}

.error-container {
    display: grid;
    gap: 1.6rem;
    text-align: center;
    justify-items: center;
    margin: 9.6rem 3.2rem;

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
