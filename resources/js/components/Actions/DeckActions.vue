<script setup>
import {computed, ref} from "vue";
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";
import AppTooltip from "../AppTooltip.vue";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const isAuthor = computed(() => {
    return UserStore.user?.id === props.model.author.id;
})

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const deleteDeck = () => {
    if (!confirm('Are you sure you want to delete this Deck?')) return;

    router.delete(route('decks.destroy', props.model.id));
};

const copyDeck = (event) => {
    if (!confirm('Are you sure you want to create a copy of this Deck?')) return

    router.post(route('decks.copy', props.model.id))
};

const copyLink = (event) => {
    event.preventDefault();

    navigator.clipboard.writeText(route('decks.show', props.model.id)).then(function () {
        alert('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
};

const tooltip = ref(null);
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Library/Decks/Show'"
            :href="route('decks.show', model.id)" role="menuitem" tabindex="-1">
            View Deck
        </Link>
        <template v-if="isAuthor">
            <Link :href="route('deck-master.build', model.id)" role="menuitem" tabindex="-1">
                Edit Deck
            </Link>
            <button @click="deleteDeck" role="menuitem" tabindex="-1">
                Delete Deck
            </button>
        </template>

        <template v-if="UserStore.isUser">
            <Link :href="model.terms_count > 0 ? route('deck-master.study', model.id) : '#'" role="menuitem"
                  tabindex="-1"
                  :class="{'disabled': model.terms_count < 1}"
                  @mousemove="model.terms_count < 1 && tooltip.showTooltip('The Deck is empty.', $event);"
                  @mouseleave="model.terms_count < 1 && tooltip.hideTooltip()"
            >
                Study Deck
            </Link>
            <template v-if="UserStore.isStudent">
                <Link v-if="model.lesson" :href="route('lessons.show', model.lesson.global_position)" role="menuitem" tabindex="-1">
                    View Lesson
                </Link>
                <Link :href="route('scores.history', { scorable_type: 'deck', scorable_id: model.id })" role="menuitem" tabindex="-1">
                    View Scores
                </Link>
            </template>
            <button @click="copyDeck" role="menuitem" tabindex="-1">
                Copy Deck
            </button>
            <button @click="copyLink" role="menuitem" tabindex="-1">
                Share Link
            </button>
            <form :action="route('decks.export', model.id)" method="POST">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" role="menuitem" tabindex="-1">
                    Export CSV
                </button>
            </form>
        </template>
    </ContextActions>
    <AppTooltip ref="tooltip"/>
</template>
