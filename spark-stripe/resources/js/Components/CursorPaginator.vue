<template>
    <div v-if="(paginator.prev_page_url !== null || paginator.next_page_url !== null)" v-cloak class="flex select-none flex-wrap items-center justify-center" v-bind="$attrs">
        <span class="mr-3">
          <Link v-if="(paginator.prev_page_url !== null)" :preserve-scroll="preserveScroll" :only="[this.reloadKey]" class="mx-1 flex h-10 w-7 items-center justify-center rounded text-xs text-gray-600 hover:text-teal-400 focus:text-teal-400" :href="paginator.prev_page_url"><chevron-icon class="h-4 rotate-180 transform" /></Link>
          <span v-else class="mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded text-xs text-gray-400"><chevron-icon class="h-4 rotate-180 transform" /></span>
        </span>
        <span class="ml-3">
          <Link v-if="(paginator.next_page_url !== null)" :preserve-scroll="preserveScroll" :only="[this.reloadKey]" class="mx-1 flex h-10 w-7 items-center justify-center rounded text-xs text-gray-600 hover:text-teal-400 focus:text-teal-400" :href="paginator.next_page_url"><chevron-icon class="h-4" /></Link>
          <span v-else class="mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded text-xs text-gray-400"><chevron-icon class="h-4" /></span>
        </span>
    </div>
</template>

<script>
    import { Link } from '@inertiajs/vue2';
    import ChevronIcon from './../Icons/ChevronIcon.vue';

    export default {
        components: {
            ChevronIcon,
            Link,
        },

        inheritAttrs: false,

        props: {
            paginator: Object,
            reloadKey: String,
            preserveScroll: {
                type: Boolean,
                default: false,
            },
        },
    }
</script>
