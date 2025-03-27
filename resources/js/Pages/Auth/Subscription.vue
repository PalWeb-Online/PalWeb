<script setup>
import Layout from "../../Shared/Layout.vue";
import SubscriptionTier from "../../components/SubscriptionTier.vue";
import AppTip from "../../components/AppTip.vue";

defineProps({
    denied: {type: Boolean, default: false}
})

defineOptions({
    layout: Layout,
});
</script>
<template>
    <Head title="Subscribe"/>
    <div id="app-head">
        <h1>Subscription</h1>
    </div>
    <div id="app-body">
        <AppTip v-if="denied">
            <p>Sorry, but you currently don't have access to this content.
                Please review & update your Subscription tier & try again.</p>
        </AppTip>
        <div class="tiers-array">
            <SubscriptionTier tier="pal"/>
            <SubscriptionTier tier="student"/>
        </div>
    </div>
</template>
