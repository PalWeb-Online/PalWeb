<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3';
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    model: Object,
});

const deleteSentence = () => {
    if (!confirm('Are you sure you want to delete this Sentence?')) return;

    router.delete(route('sentences.destroy', props.model.id));
};
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Library/Sentences/Show'" :href="route('sentences.show', model.id)" role="menuitem" tabindex="-1">
            View Sentence
        </Link>
        <Link :href="route('speech-maker.sentence', model.id)" role="menuitem" tabindex="-1">
            Edit Sentence
        </Link>
        <button @click="deleteSentence" role="menuitem" tabindex="-1">
            Delete Sentence
        </button>
    </ContextActions>
</template>
