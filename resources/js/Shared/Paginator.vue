<script setup>
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
            <template v-for="link in filteredLinks" :key="link.url">
                <Link v-if="link.url" :href="link.url"
                      :class="{ active: link.active }"
                      preserve-scroll
                >
                    <span v-if="!link.label.includes('Previous') && !link.label.includes('Next')">
                        {{ link.label }}
                    </span>
                </Link>
                <div class="disabled" v-else>
                    {{ link.label }}
                </div>
            </template>
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
