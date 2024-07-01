<template>
    <div class="space-y-6">
        <div class="bg-white sm:rounded-lg shadow-sm overflow-hidden" v-for="plan in plans">
            <plan :plan="plan" :seat-name="seatName" />

            <div class="px-6 py-4 bg-gray-100 bg-opacity-50 border-t border-gray-100 text-right">
                <spark-button @click.native="$emit('plan-selected', plan)" v-if="! currentPlan || (currentPlan && currentPlan.id != plan.id)">
                    {{ __('Subscribe') }}
                </spark-button>

                <div class="flex justify-end items-center" v-if="currentPlan && currentPlan.id == plan.id">
                    <div class="ml-1 text-sm text-gray-400">{{ __('Currently Subscribed') }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Plan from './../Components/Plan';
    import SparkButton from './../Components/Button';

    export default {
        components: {
            Plan,
            SparkButton,
        },

        props: ['plans', 'interval', 'currentPlan', 'seatName'],
    }
</script>
