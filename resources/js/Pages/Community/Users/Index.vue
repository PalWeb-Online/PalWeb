<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import DeckItem from "../../../components/DeckItem.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import UserItem from "../../../components/UserItem.vue";
import {route} from "ziggy-js";

defineProps({
    latestDecks: Array,
    popularDecks: Array,
    featuredDeck: Object,
    latestAudios: Array,
    topUsers: Array,
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Community Hub"/>
    <div id="app-head">
        <Link :href="route('users.index')"><h1>Community</h1></Link>
    </div>

    <div id="app-body">
        <div class="community-portal">
            <div class="community-portal-head">
                <div class="community-portal-title">
                    <img src="/img/tomato.svg" alt="Icon"/>
                    <div>Decks</div>
                </div>
                <div class="community-portal-blurb">
                    What everyone has been studying lately. How about you?
                </div>
            </div>
            <!--            <a href="{{ route('decks.index') }}" class="portal-button">Browse</a>-->
            <!--            <a href="{{ route('decks.create') }}" class="portal-button">Create</a>-->

            <div class="decks-featured">
                <div class="deck-flashcard-grid">
                    <DeckFlashcard :model="featuredDeck"/>
                </div>
                <div class="decks-list">
                    <div class="featured-title m" style="text-transform: none">Latest</div>
                    <DeckItem v-for="deck in latestDecks" :model="deck" size="s"/>
                </div>
                <div v-if="popularDecks.length > 0" class="decks-list popular">
                    <div class="featured-title l" style="text-transform: none">Popular</div>
                    <DeckItem v-for="deck in popularDecks" :model="deck" size="l"/>
                </div>
            </div>
        </div>


        <div class="community-portal">
            <div class="community-portal-head">
                <div class="community-portal-title">
                    <img src="/img/olive.svg" alt="Icon"/>
                    <div>Audios</div>
                </div>
                <div class="community-portal-blurb">
                    Hear it from the horse's mouth.
                </div>
            </div>
            <!--            <a href="{{ route('audios.index') }}" class="portal-button">Browse</a>-->
            <!--            <a href="{{ route('audios.record') }}" class="portal-button">Create</a>-->

            <div v-if="latestAudios.length > 0" class="audios-list">
                <div class="featured-title m" style="text-transform: none">Latest</div>
                <PronunciationItem v-for="audio in latestAudios" :model="audio.pronunciation" :audio="audio"/>
            </div>
        </div>


        <div class="community-portal">
            <div class="community-portal-head">
                <div class="community-portal-title">
                    <img src="/img/orange.svg" alt="Icon"/>
                    <div>Pals</div>
                </div>
                <div class="community-portal-blurb">
                    Shout-out to the most helpful & prolific Pals in the PalWeb Community!
                </div>
            </div>

            <div class="users-featured">
                <UserItem v-for="user in topUsers" :user="user" size="s"/>
            </div>
        </div>

    </div>
</template>
