<template>
    <div>
        <div class="px-6 py-4 bg-red-100 bg-opacity-25 text-red-900 border border-red-200 sm:rounded-lg shadow-sm" v-if="errors.length > 0">
            {{ __('Whoops! Something went wrong.') }}

            <ul class="mt-4 ml-5 text-sm list-disc">
                <li v-for="error in errors">
                    {{ error }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['errors']
    }
</script>
