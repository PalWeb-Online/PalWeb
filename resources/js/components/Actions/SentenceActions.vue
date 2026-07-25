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
            {{ $t('actions.common.view', { model: $t('actions.models.sentence') }) }}
        </Link>
        <template v-if="UserStore.isAdmin">
            <Link :href="route('speech-maker.sentence', model.id)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.sentence') }) }}
            </Link>
            <button @click="deleteSentence(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.sentence') }) }}
            </button>
        </template>
    </ContextActions>
</template>
