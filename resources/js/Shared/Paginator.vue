<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import {computed} from "vue";

const props = defineProps({
   links: Array
});

const filteredLinks = computed(() => {
    return props.links.slice(1, -1);
});
</script>

<template>
    <div id="paginator">
        <div class="pagination">
            <Link v-for="link in filteredLinks" :key="link.url" :href="link.url"
                  :class="{ active: link.active, disabled: !link.url }"
                  preserve-scroll
            >
                <span v-if="!link.label.includes('Previous') && !link.label.includes('Next')">{{ link.label }}</span>
            </Link>
        </div>

<!--        <div id="item-count">-->
<!--            @if ($paginator->firstItem())-->
<!--            <span class="font-medium">{{ $paginator->firstItem() }}</span>-->
<!--            - -->
<!--            <span class="font-medium">{{ $paginator->lastItem() }}</span>-->
<!--            @else-->
<!--            {{ $paginator->count() }}-->
<!--            @endif-->
<!--            {!! __('of') !!}-->
<!--            <span class="font-medium">{{ $paginator->total() }}</span>-->
<!--        </div>-->
    </div>
</template>
