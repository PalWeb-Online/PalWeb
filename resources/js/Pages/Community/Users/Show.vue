<script setup>
import Layout from "../../../Shared/Layout.vue";
import UserItem from "../../../components/UserItem.vue";
import BadgeItem from "../../../components/BadgeItem.vue";
import SpeakerContainer from "../../../components/SpeakerContainer.vue";
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
    <div id="app-head">
        <Link :href="route('users.index')"><h1>Community</h1></Link>
    </div>
    <div id="app-body">
        <AppTip v-if="user.id === UserStore.user.id && !UserStore.user.is_verified">
            <p><b>Welcome to PalWeb! In order to to access all of the site's features, you must verify your email
                address using the link sent to your inbox. If you need, you can send yourself a new link using the
                button in the sidebar menu, under your avatar.</b></p>
        </AppTip>
        <AppTip v-if="user.id === UserStore.user.id && user.private">
            <p>Your Profile is currently set to Private. Only you may view this page. Others may still interact with
                your created Decks that you have not set to Private, but the author will be listed as Anonymous.</p>
        </AppTip>

        <div class="user-container">
            <div class="action-buttons" v-if="user.id === UserStore.user.id">
                <img :src="`/img/${user.private ? 'lock.svg' : 'globe-africa.svg'}`"
                     alt="Privacy"
                />
                <Link :href="route('users.edit', user.username)">
                    <img src="/img/pencil.svg" alt="Edit"/>
                </Link>
            </div>
            <UserItem :user="user" size="l" comment tags/>
        </div>

        <div class="badges-container">
            <div class="featured-title l">Badges</div>
            <div class="badge-wrapper">
                <BadgeItem v-for="badge in unlockedBadges" :badge="badge" :key="badge.id"/>
            </div>

            <img class="popout" src="/img/star.svg" alt="Star"/>
        </div>

        <SpeakerContainer v-if="speaker" :speaker="speaker"/>

        <template v-if="decks.length > 0">
            <div class="decks-list">
                <div class="featured-title m">Decks: {{ decks.length }}</div>
                <DeckItem v-for="deck in decks" :model="deck"/>
            </div>
        </template>
        <template v-else>
            <AppTip>
                <p>{{ user.name }} has not created any public Decks yet.</p>
            </AppTip>
        </template>
    </div>
</template>
