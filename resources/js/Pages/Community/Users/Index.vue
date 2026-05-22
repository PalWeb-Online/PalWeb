<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import DeckItem from "../../../components/DeckItem.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import {route} from "ziggy-js";
import UserScorecard from "../../../components/UserScorecard.vue";

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
        <Link :href="route('users.index')"><h1>hub</h1></Link>
    </div>

    <div id="app-body">
        <div class="app-body-section">
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
            <!--            <a href="{{ route('record-wizard.index') }}" class="portal-button">Create</a>-->

            <div v-if="latestAudios.length > 0" class="model-list">
                <div class="featured-title m" style="text-transform: none">Latest</div>
                <PronunciationItem v-for="audio in latestAudios" :model="audio.pronunciation" :audio="audio"/>
            </div>

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
                <UserScorecard v-for="user in topUsers" :user="user"/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.community-portal-head {
    display: grid;
    margin-block-end: 4.8rem;

    .community-portal-title {
        display: grid;
        align-items: end;
        grid-template-areas: 'overlap';
        font-size: clamp(4.8rem, 12vw, 9.6rem);

        & > * {
            grid-area: overlap;
        }

        img {
            width: 2em;
            justify-self: end;
            transform: translateY(0.5em);
        }

        div {
            justify-self: start;
            font-family: var(--display-font);
            padding-inline: 0.33em;
            text-transform: uppercase;
            color: var(--color-dark-primary);
            background: var(--color-polar-light);
        }
    }

    .community-portal-blurb {
        text-align: start;
        background: var(--color-dark-primary);
        color: var(--color-polar-light);
        font-size: clamp(1.4rem, 3vw, 2.0rem);
        padding: 0.5em 1em;
        margin-block-start: -0.25em;
        font-weight: 700;
        z-index: 1;
        filter: drop-shadow(-0.4rem 0.4rem 0 var(--color-accent-medium));
    }
}

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
