<template>
    <div class="px-6 sm:px-0 flex items-center">
        <span class="font-bold text-sm text-gray-600 uppercase">
            <template v-if="$page.props.defaultInterval == 'monthly'">{{ __('Monthly') }}</template>
            <template v-if="$page.props.defaultInterval == 'yearly'">{{ __('Yearly') }}</template>
        </span>

        <button role="checkbox" tabindex="0" aria-checked="false" :class="$page.props.brandColor" class="ml-3 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none" @click="$emit('toggled')">
            <span aria-hidden="true"
                :class="{ 'translate-x-0': showingDefaultIntervalPlans, 'translate-x-5': ! showingDefaultIntervalPlans }"
                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
            </span>
        </button>

        <span class="ml-3 font-bold text-sm text-gray-600 uppercase">
            <template v-if="$page.props.defaultInterval == 'yearly'">{{ __('Monthly') }}</template>
            <template v-if="$page.props.defaultInterval == 'monthly'">{{ __('Yearly') }}</template>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['showingDefaultIntervalPlans'],
    }
</script>
