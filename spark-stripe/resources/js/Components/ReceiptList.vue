<template>
    <div>
        <div class="bg-white sm:rounded-lg shadow-sm divide-y divide-gray-100">
            <div class="flex items-center px-6 py-4" v-for="receipt in receiptsData()" :key="receipt.id">
                <div class="text-sm w-full">
                    {{ receipt.date }}
                </div>

                <div class="text-sm w-full">
                    <div class="px-2">
                        {{ receipt.amount }}
                    </div>
                </div>

                <div class="text-sm w-full">
                    <span v-if="receipt.status === 'open'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ __('Unpaid') }}
                    </span>
                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ __('Paid') }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 shrink-0 flex items-center justify-end">
                    <div class="w-52 text-right">
                        <span v-if="receipt.status === 'open'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>

                            <button
                                @click="$emit('payment-retried', receipt)"
                                class="underline hover:text-gray-500"
                                type="button"
                            >
                                {{ __('Retry Payment') }}
                            </button>

                            <span class="mx-2">|</span>
                        </span>

                        <a class="underline hover:text-gray-500" :href="receipt.receipt_url" target="_blank" :title="__('Download Receipt')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <cursor-paginator v-if="hasPaginator()" class="mt-4" :preserve-scroll="true" :paginator="receipts" :reload-key="reloadKey" />
    </div>
</template>

<script>
    import CursorPaginator from './../Components/CursorPaginator';

    export default {
        components: {
            CursorPaginator,
        },

        props: ['receipts', 'reloadKey'],

        methods: {
            hasPaginator() {
                return 'data' in this.receipts;
            },

            receiptsData() {
                return this.receipts.data ?? this.receipts;
            },
        },
    }
</script>
