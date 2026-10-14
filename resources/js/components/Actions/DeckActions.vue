<script setup>
import {computed, ref} from "vue";
import {route} from 'ziggy-js';
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";
import AppTooltip from "../AppTooltip.vue";
import {useDeck} from "../../composables/decks/useDeck.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const isAuthor = computed(() => {
    return UserStore.user?.id === props.model.author.id;
})

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const {deleteDeck, copyDeck, copyLink} = useDeck();

const shareDeck = (event) => {
    event.preventDefault();

    copyLink(props.model);
};

const tooltip = ref(null);
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <RouterLink v-if="$page.component !== 'Library/Decks/Show'"
            :to="route('decks.show', model.id)" role="menuitem" tabindex="-1">
            {{ $t('actions.common.view', { model: $t('actions.models.deck') }) }}
        </RouterLink>
        <template v-if="isAuthor || UserStore.isAdmin">
            <RouterLink :to="{ name: 'deck-master.build', params: { deck: model.id } }" role="menuitem" tabindex="-1">
                {{ $t('actions.common.edit', { model: $t('actions.models.deck') }) }}
            </RouterLink>
            <button @click="deleteDeck(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.delete', { model: $t('actions.models.deck') }) }}
            </button>
        </template>

        <template v-if="UserStore.isUser">
            <RouterLink :to="model.terms_count > 0 ? route('deck-master.study', model.id) : '#'" role="menuitem"
                  tabindex="-1"
                  :class="{'disabled': model.terms_count < 1}"
                  @mousemove="model.terms_count < 1 && tooltip.showTooltip($t('actions.deck.empty'), $event);"
                  @mouseleave="model.terms_count < 1 && tooltip.hideTooltip()"
            >
                {{ $t('actions.deck.study') }}
            </RouterLink>
            <template v-if="UserStore.isStudent">
                <RouterLink v-if="model.lesson" :to="{ name: 'lessons.show', params: { lesson: model.lesson.global_position } }" role="menuitem" tabindex="-1">
                    {{ $t('actions.common.view', { model: $t('actions.models.lesson') }) }}
                </RouterLink>
                <RouterLink :to="{
                    name: 'scores.history',
                    params: {
                        scorable_type: 'deck',
                        scorable_id: model.id
                    }
                }" role="menuitem" tabindex="-1">
                    {{ $t('actions.common.view-scores') }}
                </RouterLink>
            </template>
            <button @click="copyDeck(model)" role="menuitem" tabindex="-1">
                {{ $t('actions.common.copy', { model: $t('actions.models.deck') }) }}
            </button>
            <button @click="shareDeck" role="menuitem" tabindex="-1">
                {{ $t('actions.deck.share-link') }}
            </button>
            <form :action="route('decks.export', model.id)" method="POST">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" role="menuitem" tabindex="-1">
                    {{ $t('actions.deck.export-csv') }}
                </button>
            </form>
        </template>
    </ContextActions>
    <AppTooltip ref="tooltip"/>
</template>
