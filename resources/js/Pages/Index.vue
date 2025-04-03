<script setup>
import Layout from "../Shared/Layout.vue";
import {route} from "ziggy-js";
import {nextTick, onBeforeUnmount, onMounted, ref} from "vue";
import DeckFlashcard from "../components/DeckFlashcard.vue";
import UserItem from "../components/UserItem.vue";
import SentenceContainer from "../components/SentenceContainer.vue";
import HomepageHero from "../components/HomepageHero.vue";
import DeckContainer from "../components/DeckContainer.vue";

defineProps({
    count: Object,
    users: Array,
    decks: Array,
    sentences: Array,
    featuredUser: Object,
    featuredDeck: Object,
});

const carousels = [];
const numberOfDuplications = 1;

const duplicateCarouselItems = (carousel) => {
    const items = Array.from(carousel.children);

    if (items.length === 0) return;

    for (let i = 0; i < numberOfDuplications; i++) {
        items.forEach((item) => {
            const clone = item.cloneNode(true);
            clone.setAttribute("aria-hidden", "true");
            carousel.appendChild(clone);
        });
    }
};

onMounted(async () => {
    await nextTick();
    carousels.length = 0;
    carousels.push(...document.querySelectorAll(".carousel-track"));
    carousels.forEach((carousel) => duplicateCarouselItems(carousel));
});

onBeforeUnmount(() => {
    carousels.forEach((carousel) => {
        carousel.innerHTML = "";
    });
});

defineOptions({
    layout: Layout
});
</script>
<template>
    <Head title="Home"/>
    <div id="app-body" class="homepage">
        <div class="homepage-hero-wrapper">
            <Link :href="route('wiki.show', 'release-notes')" class="feature-callout">v2.0 Release Notes -></Link>
            <HomepageHero/>
            <!--            <div class="hero-blurb">-->
            <!--                Learning a language means making it yours.-->
            <!--            </div>-->
        </div>

        <div class="homepage-panel-wrapper accent-light">
            <img class="popout" src="/img/watermelon.svg" alt="watermelon"/>

            <div class="feature-panel-content" style="text-align: center">
                <div class="feature-panel-title">language is a web</div>
                <div class="feature-panel-subtitle">PalWeb is the Web of Palestinian Arabic</div>
            </div>

            <div class="model-counter-wrapper">
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.terms }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">Terms</span>
                    </div>
                </div>
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.sentences }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">Sentences</span>
                    </div>
                </div>
            </div>

            <div class="feature-panel inline">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">hypertext dictionary</div>
                    <div class="feature-panel-subtitle">Build your vocabulary. Build Decks.</div>
                    <div class="feature-panel-description">Say goodbye to the tedium of piecing together your own
                        vocabulary
                        sets from bits of dubious information. Use the power of the PalWeb Dictionary to build your own
                        custom Decks in a flash. Need some inspiration? Browse the Deck Library to see all the Decks
                        others
                        have made — & even copy a Deck to put your own spin on someone else's idea!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <DeckContainer :model="featuredDeck"/>
                </div>
            </div>

            <div class="feature-panel inline reverse">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">interactive phrasebook</div>
                    <div class="feature-panel-subtitle">Build your vocabulary. Build Decks.</div>
                    <div class="feature-panel-description">Say goodbye to the tedium of piecing together your own
                        vocabulary
                        sets from bits of dubious information. Use the power of the PalWeb Dictionary to build your own
                        custom Decks in a flash. Need some inspiration? Browse the Deck Library to see all the Decks
                        others
                        have made — & even copy a Deck to put your own spin on someone else's idea!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <SentenceContainer :model="sentences[0]" size="l" :key="sentences[0].id"/>
                </div>
            </div>
        </div>

        <div class="homepage-panel-wrapper pastel-light">
            <div class="feature-panel-content">
                <div class="feature-panel-subtitle">hassle-free language learning is here.</div>
            </div>

            <div class="feature-panel inline">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">deck master: build</div>
                    <div class="feature-panel-subtitle">Build your vocabulary. Build Decks.</div>
                    <div class="feature-panel-description">Say goodbye to the tedium of piecing together your own
                        vocabulary
                        sets from bits of dubious information. Use the power of the PalWeb Dictionary to build your own
                        custom Decks in a flash. Need some inspiration? Browse the Deck Library to see all the Decks
                        others
                        have made — & even copy a Deck to put your own spin on someone else's idea!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/db-demo.mov">
                    </video>
                </div>
            </div>

            <div class="carousel-wrapper">
                <div class="carousel-track" style="animation-direction: reverse">
                    <DeckFlashcard v-for="deck in decks" :model="deck" :key="'deck-carousel' + deck.id"/>
                </div>
            </div>

            <div class="feature-panel inline reverse">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">deck master: study</div>
                    <div class="feature-panel-subtitle">Flashy new ways to study.</div>
                    <div class="feature-panel-description">Tired of micro-managing third-party flashcard applications?
                        Study your Deck right here with the interactive Card Viewer! The Card Viewer gives you total
                        flexibility to adjust how you view your Deck with just one click. (Quizzes &
                        other modes will be coming soon!)
                    </div>
                </div>
                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/cv-demo.mov">
                    </video>
                </div>
            </div>
        </div>

        <div class="homepage-panel-wrapper accent-light">
            <div>
                <img src="/img/globe-america.svg" class="world" alt="America"/>
                <img src="/img/globe-africa.svg" class="world" alt="Africa"/>
                <img src="/img/globe-asia.svg" class="world" alt="Asia"/>
            </div>

            <div class="feature-panel-content" style="text-align: center">
                <div class="feature-panel-title">we create. you learn.</div>
                <div class="feature-panel-subtitle">everything on PalWeb is made by others like you</div>
            </div>

            <div class="carousel-wrapper">
                <div class="carousel-track">
                    <UserItem v-for="user in users" :user="user" :key="'user-carousel' + user.id" size="s"/>
                </div>
            </div>

            <div class="model-counter-wrapper">
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.decks }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">Decks</span>
                    </div>
                </div>
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.audios }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">Audios</span>
                    </div>
                </div>
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.users }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">Pals</span>
                    </div>
                </div>
            </div>

            <div class="feature-panel inline">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">building community</div>
                    <div class="feature-panel-subtitle">Connect & share with others.</div>
                </div>
                <div class="feature-panel-feature">
                    <div class="user-container">
                        <UserItem :user="featuredUser" size="l" comment/>
                    </div>
                </div>
            </div>

            <div class="feature-panel inline reverse">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">record wizard</div>
                    <div class="feature-panel-subtitle">Let your voice shine through.</div>
                    <div class="feature-panel-description">Originally developed to easily source pronunciation samples
                        from
                        native speakers, the Record Wizard will be open for all to contribute audios to the site.
                        Fluency
                        level is indicated for each Speaker, so don't be shy — every word can have as many audios as
                        there
                        are Arabic speakers in the world!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/rw-demo.mov">
                    </video>
                </div>
            </div>

            <div class="feature-panel-content">
                <div class="feature-panel-subtitle">& so much more ...</div>
            </div>

            <div class="portal-button-wrapper">
                <div class="portal-button-head">
                    what will you create?
                </div>
                <div class="portal-button-body">
                    <Link :href="route('signup')" class="portal-button">Get Started!</Link>
                </div>
            </div>
        </div>
    </div>
</template>
