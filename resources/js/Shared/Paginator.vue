<script setup>
import { computed } from "vue";

const props = defineProps({
    links: Array,
    pageNumbers: Array,
    currentPage: Number,
    goToPage: Function,
});

const useNewMode = computed(() => props.pageNumbers && props.goToPage);

const filteredLinks = computed(() => {
    if (!props.links) return [];
    return props.links.slice(1, -1);
});
</script>

<template>
    <div id="paginator">
        <div class="pagination">
            <template v-if="useNewMode">
                <template v-for="page in pageNumbers" :key="page">
                    <a
                        v-if="page !== '...'"
                        :class="{ active: page === currentPage }"
                        style="cursor: pointer"
                        @click="goToPage(page)"
                    >{{ page }}</a>
                    <div v-else class="disabled">...</div>
                </template>
            </template>

            <template v-else>
                <template v-for="link in filteredLinks" :key="link.url">
                    <RouterLink
                        v-if="link.url"
                        :to="link.url"
                        :class="{ active: link.active }"

                    >
                        <span v-if="!link.label.includes('Previous') && !link.label.includes('Next')">
                            {{ link.label }}
                        </span>
                    </RouterLink>
                    <div class="disabled" v-else>{{ link.label }}</div>
                </template>
            </template>
        </div>
    </div>
</template>