<script setup>
import {Link} from '@inertiajs/inertia-vue3'
import {route} from 'ziggy-js';

defineProps({
    root: Object,
    terms: Object,
});
</script>
<template>
    <div class="root-container">
        <div class="root-root">
            <div class="root-ar">{{ root.ar }}</div>
            <div class="root-en">({{ root.en }})</div>
        </div>

        <div v-if="Object.keys(root.terms).length > 1" class="root-terms">
            <template v-for="term in root.terms">
                <Link v-if="!terms.some(model => model.id === term.id)" :href="route('terms.show', term.slug)">
                    {{ term.term }} ({{ term.translit }})
                </Link>
            </template>
        </div>
    </div>
</template>
