<script setup>
import Layout from "../../../Shared/Layout.vue";
import UserItem from "../../../components/UserItem.vue";
import BadgeItem from "../../../components/BadgeItem.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../stores/UserStore.js";
import Paginator from "../../../Shared/Paginator.vue";
import {ref} from "vue";
import {useQueryFilters} from "../../../composables/QueryFilters.js";

const props = defineProps({
    user: Object,
    decks: Array,
    badges: Array,
    speaker: Object,
    filters: Object,
});

const UserStore = useUserStore();

const filters = ref({
    sort: props.filters.sort || 'latest',
});

const {updateFilter} = useQueryFilters(filters);

const unlockedBadges = props.badges.map(badge => ({
    ...badge,
    unlocked: props.user.badges.some(unlockedBadge => unlockedBadge.id === badge.id)
}));


defineOptions({
    layout: Layout
});
</script>

<template>
    <Head :title="`Pal: ${user.username}`"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('users.index')" class="material-symbols-rounded">home</Link>
                <div class="material-symbols-rounded" :class="user.private ? 'private' : ''">
                    {{ user.private ? 'lock' : 'public' }}
                </div>
                <div class="window-header-url">www.palweb.app/hub/users/{user}</div>
            </div>
            <div class="window-section-head">
                <h1>profile</h1>
                <Link v-if="UserStore.isAdmin || user.id === UserStore.user.id"
                      :href="route('users.edit', user.username)" class="material-symbols-rounded">edit
                </Link>
            </div>
            <AppTip v-if="user.id === UserStore.user.id && !UserStore.user.is_verified">
                <p>Welcome to PalWeb! In order to to access all of the site's features, you must verify your email
                    address using the link sent to your inbox. If you need, you can send yourself a new link using the
                    button in the sidebar menu, under your avatar.</p>
            </AppTip>
            <AppTip v-if="user.id === UserStore.user.id && user.private">
                <p>Your Profile is currently set to Private; this page is only visible to you. Others may still interact
                    with any Audios you have recorded or any Decks you have created that you have not set to Private,
                    but the creator will be listed as
                    Anonymous.</p>
            </AppTip>
            <UserItem :user="user" size="l" comment tags>
                <div v-if="user.teacher" class="user-item comment-item l">
                    <div class="user-data-wrapper">
                        <div class="user-comment">
                            <div class="user-comment-title">
                                <img class="popout" src="/img/star.svg" alt="Star"/>
                                <span>teacher bio</span>
                            </div>
                            <div v-if="user.teacher.bio" class="user-comment-content">
                                {{ user.teacher.bio }}
                            </div>
                        </div>
                    </div>
                </div>
                <!--                only admins can create Teacher profiles for now -->
                <Link v-else-if="UserStore.isAdmin" class="portal-button"
                      :href="route('users.edit', user.username)"
                      style="margin-block: 3.2rem; justify-self: center"
                >
                    Create Teacher Profile
                </Link>

                <SpeakerItem v-if="speaker" :speaker="speaker"/>
            </UserItem>

            <div class="window-section-head">
                <h2>decks</h2>
            </div>
            <div class="search-filters-container">
                <div class="search-filters">
                    <select v-model="filters.sort">
                        <option value="latest">by Latest</option>
                        <option value="alphabetical">Alphabetical</option>
                    </select>
                </div>
            </div>
            <template v-if="decks.data.length > 0">
                <div class="model-list index-list">
                    <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
                </div>
                <Paginator :links="decks.meta.links"/>
            </template>
            <template v-else>
                <AppTip>
                    <p>{{ user.name }} has not created any public Decks yet.</p>
                </AppTip>
            </template>
        </div>

        <div class="badges-container">
            <div class="featured-title l">Badges</div>
            <div class="badge-wrapper">
                <BadgeItem v-for="badge in unlockedBadges" :badge="badge" :key="badge.id"/>
            </div>

            <img class="popout" src="/img/star.svg" alt="Star"/>
        </div>
    </div>
</template>

<style scoped lang="scss">
.badges-container {
    width: 100%;
    max-width: 96rem;
    display: grid;
    gap: 1.6rem;
    padding: 0.8rem;
    position: relative;
    background: var(--color-dark-primary);

    .featured-title {
        color: white;
        margin-block-start: 0.8rem;
    }

    .badge-wrapper {
        display: grid;
        gap: 1.6rem;
        grid-template-columns: repeat(4, 1fr);
        background: white;
        border-radius: 1.2rem;
        padding: 1.6rem;
    }

    & > .popout {
        width: 6.4rem;
        position: absolute;
        top: -2.0rem;
        right: 3.2rem;
        transition: 0.3s cubic-bezier(.18, .89, .32, 1.28);
    }

    &:hover > .popout {
        transform: scale(1.2) rotate(-15deg);
    }

    @media (width >= 960px) {
        border-radius: 1.6rem;
        border: 0.2rem solid var(--color-accent-medium);
        box-shadow: -0.6rem 0.6rem 0 0 var(--color-accent-medium);

        .badge-wrapper {
            grid-template-columns: repeat(8, 1fr);
        }
    }
}
</style>
