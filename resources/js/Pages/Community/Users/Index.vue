<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import DeckItem from "../../../components/DeckItem.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import {route} from "ziggy-js";
import UserScorecard from "../../../components/UserScorecard.vue";
import Paginator from "../../../Shared/Paginator.vue";
import CommentItem from "../../../components/CommentItem.vue";
import AppHeading from "../../../components/AppHeading.vue";
import AppTip from "../../../components/AppTip.vue";

defineProps({
    teachers: Object,
    teachersCount: Number,
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
        <Link :href="route('users.index')"><h1>hub</h1></Link>
    </div>

    <div id="app-body">
        <div class="app-body-section">
            <AppHeading>
                teachers
            </AppHeading>
            <AppTip>
                <p v-if="teachersCount <= 0">Sadly, there are no Teachers on PalWeb yet.</p>
                <p>Do you teach Spoken Arabic & would like to see yourself on this page? Reach out to <b>adrian@palweb.app</b>!</p>
            </AppTip>
            <div class="comment-list" v-if="teachersCount > 0">
                <CommentItem v-for="user in teachers.data" :key="user.id" :user="user"
                             class="l"
                >
                    <div class="user-comment-content">
                        <template v-if="user.teacher.bio">
                            {{ user.teacher.bio }}
                        </template>
                        <template v-else>
                            <i>Sadly, {{ user.name }} hasn't told us anything about themselves as a Teacher
                                yet. They should probably fix that soon.</i>
                        </template>
                    </div>
                </CommentItem>
            </div>
            <Paginator v-if="teachers.meta.links.length > 3" :links="teachers.meta.links"/>

            <AppHeading>
                decks
            </AppHeading>
            <div class="decks-featured">
                <DeckFlashcard :model="featuredDeck"/>
                <div class="model-list">
                    <div class="featured-title m" style="text-transform: none">Latest</div>
                    <DeckItem v-for="deck in latestDecks" :model="deck" size="s"/>
                </div>
                <div v-if="popularDecks.length > 0" class="model-list popular">
                    <div class="featured-title l" style="text-transform: none">Popular</div>
                    <DeckItem v-for="deck in popularDecks" :model="deck" size="l"/>
                </div>
            </div>
            <Link class="portal-button" style="justify-self: center"
                  :href="route('deck-master.build')"
            >create your own!
            </Link>

            <AppHeading>
                audios
            </AppHeading>
            <div v-if="latestAudios.length > 0" class="model-list">
                <div class="featured-title m" style="text-transform: none">Latest</div>
                <PronunciationItem v-for="audio in latestAudios" :model="audio.pronunciation" :audio="audio"/>
            </div>
            <Link class="portal-button" style="justify-self: center"
                  :href="route('sound-booth.index')"
            >record your own!
            </Link>

            <AppHeading>
                creators
            </AppHeading>
            <div class="users-featured">
                <UserScorecard v-for="user in topUsers" :user="user"/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.decks-featured {
    display: grid;
    gap: 4.8rem;
    grid-template-columns: auto;
    align-items: start;
    margin-block-end: 3.2rem;

    .deck-flashcard-grid {
        display: none;
    }

    @media (width >= 960px) {
        grid-template-columns: min-content auto;

        .popular {
            grid-row: 2;
            grid-column: span 2;
        }

        .deck-flashcard-grid {
            display: flex;
        }
    }
}

.users-featured {
    display: flex;
    flex-flow: row wrap;
    justify-content: center;
    gap: 3.2rem;
}
</style>
