<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {useSentence} from "../../composables/sentences/useSentence.js";

const UserStore = useUserStore();
const {deleteSentence} = useSentence();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Library/Sentences/Show'"
              :href="route('sentences.show', model.id)" role="menuitem" tabindex="-1">
            View Sentence
        </Link>
        <template v-if="UserStore.isAdmin">
            <Link :href="route('speech-maker.sentence', model.id)" role="menuitem" tabindex="-1">
                Edit Sentence
            </Link>
            <button @click="deleteSentence(model)" role="menuitem" tabindex="-1">
                Delete Sentence
            </button>
        </template>
    </ContextActions>
</template>
