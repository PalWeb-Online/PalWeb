<script setup>
import Layout from "../../../Shared/Layout.vue";
import UserItem from "../../../components/UserItem.vue";
import BadgeItem from "../../../components/BadgeItem.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../stores/UserStore.js";

const props = defineProps({
    user: Object,
    decks: Array,
    badges: Array,
    speaker: Object,
});

const UserStore = useUserStore();

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
                <Link v-if="UserStore.isAdmin || user.id === UserStore.user.id" :href="route('users.edit', user.username)" class="material-symbols-rounded">edit</Link>
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
                <SpeakerItem v-if="speaker" :speaker="speaker"/>
            </UserItem>

            <div class="window-section-head">
                <h2>decks</h2>
            </div>
            <template v-if="decks.length > 0">
                <div class="model-list index-list">
                    <DeckItem v-for="deck in decks" :model="deck"/>
                </div>
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
