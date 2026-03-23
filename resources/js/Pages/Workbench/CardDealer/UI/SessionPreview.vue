<script setup>
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import {computed, ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import AppTooltip from "../../../../components/AppTooltip.vue";
import SessionSettings from "./SessionSettings.vue";
import {useUserStore} from "../../../../stores/UserStore.js";

const UserStore = useUserStore();

const appTooltip = ref(null);
const emit = defineEmits(['refresh']);

const props = defineProps({
    deckId: {
        type: Number,
        required: false,
    },
    scope: {
        type: String,
        required: true,
    },
    activeStats: {
        type: Object,
        required: true,
    },
    allStats: {
        type: Object,
        required: true,
    }
})

const remainingReviewCapacity = computed(() => {
    return Math.max(0, sessionSettings.value.review_limit - props.activeStats.ownedReviewedToday)
})

const remainingNewCapacity = computed(() => {
    return Math.max(0, sessionSettings.value.new_limit - props.activeStats.newReviewedToday)
})

const nonNewQueueCount = computed(() => {
    return Math.min(props.activeStats.remainingReviews, remainingReviewCapacity.value)
})

const remainingAfterNonNew = computed(() => {
    return Math.max(0, remainingReviewCapacity.value - nonNewQueueCount.value)
})

const newQueueCount = computed(() => {
    return Math.min(
        remainingNewCapacity.value,
        remainingAfterNonNew.value,
        props.activeStats.remainingNew + props.activeStats.availableNew
    )
})

const reviewQueueCount = computed(() => {
    return nonNewQueueCount.value + newQueueCount.value
})

const hasReachedTotalLimit = computed(() => {
    return hasReachedReviewLimit.value || hasReachedNewLimit.value
})

const hasReachedNewLimit = computed(() => {
    return newQueueCount.value !== props.activeStats.remainingNew
})

const hasReachedReviewLimit = computed(() => {
    return props.activeStats.remainingReviews > remainingReviewCapacity.value
})

const purgeNew = () => {
    if (confirm('Are you sure you want to delete all your New Cards?')) {
        router.post(route('cards.purge'), {}, {
            preserveScroll: true,
            onSuccess: () => {
                emit('refresh');
            },
        });
    }
}

const sessionSettings = ref({
    new_limit: UserStore.user?.preferences?.srs?.new_limit ?? 25,
    review_limit: UserStore.user?.preferences?.srs?.review_limit ?? 100,
    learning_steps: UserStore.user?.preferences?.srs?.learning_steps ?? 3,
    prompt_type: UserStore.user?.preferences?.srs?.prompt_type ?? false,
});
</script>

<template>
    <div class="score-stats-wrapper" style="width: min(96rem, 100%); gap: 1.6rem">
        <div class="featured-title m">
            Today’s Review
            <PopupWindow title="Card Dealer (Session)">
                <div class="h1">Session Preview</div>
                <p>Cards within Review Sessions belong to two main categories: <b>Review</b> & <b>New</b>
                    Cards; the count of each type of Card that is currently due is indicated below, while
                    the <b>Total Due</b> count represents the sum of both. However, the session may be
                    unable to queue up all of your Due Cards for a variety of reasons outlined here.</p>
                <p><b>1. Your Due Cards are over the Review Limit.</b> The Review Limit is the total count
                    of Cards that you can see today, including Review & New Cards. If your Due Cards are
                    over the Review Limit, priority will be given to Review Cards, then to all Owned Cards
                    (i.e. including existing New Cards that haven't been graduated yet). Only then will any
                    additional New Cards be created if any room remains. Keep in mind, then, that you may
                    see fewer New Cards than your New Limit allows if your Review Cards are filling up your
                    queue up to the Review Limit, leaving no room to create New Cards.</p>
                <p><b>2. Your New Cards are at or over the New Limit.</b> No additional New Cards will be
                    created unless there is room in the session after adding in all Owned Cards. However,
                    any existing New Cards among Owned Cards will have already counted toward the New Limit;
                    that is, all Owned New Cards must be queued up before the creation of additional New
                    Cards is considered. Also, don't forget that New Cards are created from available Terms
                    in the scope, so you may not be able to create additional New Cards if there are no more
                    available Terms. When additional New Cards may be created, the count of existing New
                    Cards is modified by adding the count of New Cards that would be created by starting the
                    session.</p>
                <p>Note that the count of Due Cards represents those that have not been reviewed yet & thus
                    remain due — not all Cards that were due today. Keep in mind that Cards you have already
                    reviewed & created today may be counting toward the Review & New Limits.</p>
                <p>Moreover, it's important to understand that the Review & New Limits are global, because
                    they are intended to control the daily cognitive load from vocabulary review. In other
                    words, if you max out your reviews by studying within one scope (e.g. a given Deck), you
                    will be unable to review any more Cards within any other scope. However, you can freely
                    enter & exit Review Sessions within different scopes as you wish. Note that this could
                    result in creating many New Cards across different scopes, so feel free to clean them up
                    with the <b>Purge</b> option. Since New Cards haven't even graduated beyond the initial
                    learning steps, you won't lose any significant progress by doing so.</p>
            </PopupWindow>
        </div>
        <div class="score-stats-container">
            <div class="score-stats-container__content">
                <div class="score-stats-highlight-wrapper">
                    <div class="score-highlight">
                        <div class="score-highlight-title">Total Due</div>
                        <div style="display: grid; justify-items: center">
                            <div v-if="scope === 'deck'" class="featured-title s"
                                 style="font-size: 3.2rem; color: var(--color-pastel-dark)">
                                {{ allStats.remainingDue }}
                            </div>
                            <template v-if="!hasReachedTotalLimit">
                                <div class="featured-title">
                                    {{ activeStats.remainingDue }}
                                </div>
                            </template>
                            <template v-else>
                                <div class="featured-title"
                                     style="text-decoration: 0.4rem var(--color-medium-primary) line-through">
                                    {{ activeStats.remainingDue }}
                                </div>
                                <div class="featured-title s"
                                     style="font-size: 4.8rem; color: var(--color-medium-secondary)">
                                    {{ reviewQueueCount }}
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="score-highlight">
                        <div class="score-highlight-title">New</div>
                        <div style="display: grid; justify-items: center">
                            <div v-if="scope === 'deck'" class="featured-title s"
                                 style="font-size: 3.2rem; color: var(--color-pastel-dark)">
                                {{ allStats.remainingNew }}
                            </div>
                            <template v-if="!hasReachedNewLimit">
                                <div class="featured-title">
                                    {{ activeStats.remainingNew }}
                                </div>
                            </template>
                            <template v-else>
                                <div class="featured-title"
                                     style="text-decoration: 0.4rem var(--color-medium-primary) line-through">
                                    {{ activeStats.remainingNew }}
                                </div>
                                <div class="featured-title s"
                                     style="font-size: 4.8rem; color: var(--color-medium-secondary)">
                                    {{ newQueueCount }}
                                </div>
                            </template>
                        </div>

                        <button class="material-symbols-rounded"
                                @click="purgeNew"
                                @mousemove="appTooltip.showTooltip('Purges all your New Cards.', $event);"
                                @mouseleave="appTooltip.hideTooltip()"
                        >cycle
                        </button>
                    </div>
                    <div class="score-highlight">
                        <div class="score-highlight-title">Review</div>
                        <div v-if="scope === 'deck'" class="featured-title s"
                             style="font-size: 3.2rem; color: var(--color-pastel-dark)">
                            {{ allStats.remainingReviews }}
                        </div>
                        <template v-if="!hasReachedReviewLimit">
                            <div class="featured-title">
                                {{ activeStats.remainingReviews }}
                            </div>
                        </template>
                        <template v-else>
                            <div class="featured-title"
                                 style="text-decoration: 0.4rem var(--color-medium-primary) line-through">
                                {{ activeStats.remainingReviews }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.8rem;">
                                <div class="featured-title s"
                                     style="font-size: 4.8rem; color: var(--color-medium-secondary)">
                                    {{ reviewQueueCount }}
                                </div>

                                <span class="material-symbols-rounded"
                                      @mousemove="appTooltip.showTooltip('Your Due Cards are over your Review Limit! Priority will be given to Review Cards, then to all Owned Cards; New Cards will be added last.', $event);"
                                      @mouseleave="appTooltip.hideTooltip()"
                                >help
                                    </span>
                            </div>
                        </template>
                    </div>
                </div>
                <Link class="featured-title s session-start-button"
                      v-if="scope === 'all'" :href="route('card-dealer.review')">start
                </Link>
                <Link class="featured-title s session-start-button"
                      v-else-if="scope === 'deck' && deckId" :href="route('card-dealer.review', deckId)">start
                </Link>
            </div>
        </div>

        <SessionSettings :settings="sessionSettings"/>
    </div>

    <AppTooltip ref="appTooltip"/>
</template>

<style scoped lang="scss">
.score-stats-container__content {
    padding: 0;
    gap: 0;
}

.score-stats-highlight-wrapper {
    padding: 3.2rem;
}

.session-start-button {
    display: block;
    background: var(--color-medium-primary);
    text-align: center;
    padding: 1.6rem 1.6rem 1.8rem;
    color: white;
}
</style>
