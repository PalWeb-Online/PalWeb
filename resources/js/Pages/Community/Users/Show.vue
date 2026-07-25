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
import {computed, ref} from "vue";
import {useQueryFilters} from "../../../composables/QueryFilters.js";
import UserActions from "../../../components/Actions/UserActions.vue";
import {useUser} from "../../../composables/users/useUser.js";
import AppHeading from "../../../components/AppHeading.vue";

const props = defineProps({
    user: Object,
    decks: Array,
    badges: Array,
    speaker: Object,
    filters: Object,
});

const UserStore = useUserStore();
const {isStudent} = useUser(props.user);

const canCreateTeacher = computed(() => {
    return isStudent.value && UserStore.isAdmin
});

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
                <h1>{{ $t('user.profile')}}</h1>
                <UserActions v-if="UserStore.isAdmin" :model="user"/>
                <Link v-else-if="user.id === UserStore.user.id"
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
                <div v-if="user.teacher && (isStudent || user.id === UserStore.user.id || UserStore.isAdmin)"
                     class="user-item comment-item l">
                    <div class="user-data-wrapper">
                        <div class="user-comment">
                            <div class="user-comment-title">
                                <img class="popout" src="/img/star.svg" alt="Star"/>
                                <span>{{ $t('teacher.bio') }}</span>
                            </div>
                            <AppTip v-if="!isStudent && (user.id === UserStore.user.id || UserStore.isAdmin)">
                                <p>You don't have a Student subscription, so your Teacher profile will not be visible to
                                    others. Please renew your subscription to make your Teacher profile public.</p>
                            </AppTip>
                            <div class="user-comment-content">
                                <template v-if="user.teacher.bio">
                                    {{ user.teacher.bio }}
                                </template>
                                <template v-else>
                                    <i>{{ $t('teacher.bio-placeholder', {user: user.name}) }}</i>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <Link v-else-if="canCreateTeacher" class="portal-button"
                      :href="route('users.edit', user.username)"
                      style="margin-block: 3.2rem; justify-self: center"
                >
                    {{ $t('teacher.create') }}
                </Link>

                <SpeakerItem v-if="speaker" :speaker="speaker"/>
            </UserItem>

            <div class="window-section-head">
                <h2>{{ $t('models.decks') }}</h2>
            </div>
            <div class="search-filters-container">
                <div class="search-filters">
                    <select v-model="filters.sort">
                        <option value="latest">{{ $t('search.filters.sort.latest') }}</option>
                        <option value="alphabetical">{{ $t('search.filters.sort.alphabetical') }}</option>
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
                    <p>{{ $t('pages.users.show.empty', { user: user.name }) }}</p>
                </AppTip>
            </template>
        </div>

        <div class="app-body-section">
            <AppHeading>
                {{ $t('models.badges') }}
            </AppHeading>
            <div class="badge-wrapper">
                <BadgeItem v-for="badge in unlockedBadges" :badge="badge" :key="badge.id"/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.badge-wrapper {
    display: grid;
    gap: 1.6rem;
    grid-template-columns: repeat(auto-fit, minmax(9.6rem, 1fr));
    margin-block-end: 6.4rem;
}
</style>
