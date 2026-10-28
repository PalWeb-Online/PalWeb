<script setup>
import UserItem from '../../../components/UserItem.vue';
import BadgeItem from '../../../components/BadgeItem.vue';
import SpeakerItem from '../../../components/SpeakerItem.vue';
import DeckItem from '../../../components/DeckItem.vue';
import AppTip from '../../../components/AppTip.vue';
import {useUserStore} from '../../../stores/UserStore.js';
import Paginator from '../../../Shared/Paginator.vue';
import {computed, onMounted, watch} from 'vue';
import {useQueryFilters} from '../../../composables/QueryFilters.js';
import UserActions from '../../../components/Actions/UserActions.vue';
import {useUser} from '../../../composables/users/useUser.js';
import AppHeading from '../../../components/AppHeading.vue';
import {useUserViewer} from '../../../composables/users/useUserViewer.js';
import {useRoute} from 'vue-router';
import LoadingSpinner from '../../../Shared/LoadingSpinner.vue';
import {usePaginator} from '../../../composables/usePaginator.js';

const props = defineProps({
    userUsername: {
        type: String,
        required: true,
    },
});

const UserStore = useUserStore();
const route = useRoute();

const {
    user,
    decks,
    badges,
    speaker,
    filters,
    userNotFound,
    isLoadingUser,
    reloadUser,
} = useUserViewer();

const { isStudent } = useUser(user);

const canCreateTeacher = computed(() => {
    return isStudent.value && UserStore.isAdmin;
});

useQueryFilters(filters);

const unlockedBadges = computed(() => badges.value.map(badge => ({
    ...badge,
    unlocked: user.value?.badges?.some(unlockedBadge => unlockedBadge.id === badge.id) ?? false,
})));

const currentQuery = () => ({ ...route.query });

const fetchUserPage = async (params = {}) => {
    await reloadUser(props.userUsername, {
        params,
    });
};

const {
    currentPage,
    pageNumbers,
    goToPage,
    updatePagination,
} = usePaginator(fetchUserPage);

onMounted(() => fetchUserPage(currentQuery()));

watch(() => decks.value.meta, (pagination) => {
    updatePagination(pagination);
});

watch(
    () => [props.userUsername, route.query],
    () => fetchUserPage(currentQuery()),
);
</script>

<template>
    <div id="app-body">
        <LoadingSpinner style="align-self: center" v-if="isLoadingUser"/>
        <AppTip v-else-if="userNotFound">
            <p>{{ $t('pages.common.not-found', { model: $t('actions.models.user') }) }}</p>
        </AppTip>
        <template v-else-if="user">
            <div class="window-container">
                <div class="window-header">
                    <RouterLink :to="{ name: 'users.index' }" class="material-symbols-rounded">home</RouterLink>
                    <div class="material-symbols-rounded" :class="user.private ? 'private' : ''">
                        {{ user.private ? 'lock' : 'public' }}
                    </div>
                    <div class="window-header-url">www.palweb.app/hub/users/{user}</div>
                </div>
                <div class="window-section-head">
                    <h1>{{ $t('user.profile') }}</h1>
                    <UserActions v-if="UserStore.isAdmin" :model="user"/>
                    <RouterLink v-else-if="user.id === UserStore.user.id"
                                :to="{ name: 'users.edit', params: { user: user.id } }"
                                class="material-symbols-rounded">edit
                    </RouterLink>
                </div>
                <AppTip v-if="user.id === UserStore.user.id && !UserStore.user.is_verified">
                    <p>Welcome to PalWeb! In order to to access all of the site's features, you must verify your email
                        address using the link sent to your inbox. If you need, you can send yourself a new link using
                        the
                        button in the sidebar menu, under your avatar.</p>
                </AppTip>
                <AppTip v-if="user.id === UserStore.user.id && user.private">
                    <p>Your Profile is currently set to Private; this page is only visible to you. Others may still
                        interact
                        with any Audios you have recorded or any Decks you have created that you have not set to
                        Private,
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
                                    <span>{{ $t('teacher.fields.bio') }}</span>
                                </div>
                                <AppTip v-if="!isStudent && (user.id === UserStore.user.id || UserStore.isAdmin)">
                                    <p>You don't have a Student subscription, so your Teacher profile will not be
                                        visible to
                                        others. Please renew your subscription to make your Teacher profile public.</p>
                                </AppTip>
                                <div class="user-comment-content">
                                    <template v-if="user.teacher.bio">
                                        {{ user.teacher.bio }}
                                    </template>
                                    <template v-else>
                                        <i>{{ $t('teacher.fields.bio-placeholder', { user: user.name }) }}</i>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <RouterLink v-else-if="canCreateTeacher" class="portal-button"
                                :to="{ name: 'users.edit', params: { user: user.id } }"
                                style="margin-block: 3.2rem; justify-self: center"
                    >
                        {{ $t('teacher.create') }}
                    </RouterLink>

                    <SpeakerItem v-if="speaker" :speaker="speaker"/>
                </UserItem>

                <div class="window-section-head">
                    <h2>{{ $t('components.user.sections.decks') }}</h2>
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
                    <Paginator
                        :pageNumbers="pageNumbers"
                        :currentPage="currentPage"
                        :goToPage="goToPage"
                    />
                </template>
                <template v-else>
                    <AppTip>
                        <p>{{ $t('pages.users.show.empty', { user: user.name }) }}</p>
                    </AppTip>
                </template>
            </div>

            <div class="app-body-section">
                <AppHeading>
                    {{ $t('pages.users.show.badges') }}
                </AppHeading>
                <div class="badge-wrapper">
                    <BadgeItem v-for="badge in unlockedBadges" :badge="badge" :key="badge.id"/>
                </div>
            </div>
        </template>
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
