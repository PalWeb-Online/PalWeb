<script setup>
import Layout from "../Shared/Layout.vue";
import {route} from "ziggy-js";
import {nextTick, onBeforeUnmount, onMounted} from "vue";
import DeckFlashcard from "../components/DeckFlashcard.vue";
import UserItem from "../components/UserItem.vue";
import HomepageHero from "../components/HomepageHero.vue";
import {useNavigationStore} from "../stores/NavigationStore.js";
import UserScorecard from "../components/UserScorecard.vue";

defineProps({
    count: Object,
    users: Array,
    decks: Array,
    sentences: Array,
    featuredUser: Object,
    featuredDeck: Object,
});

const NavigationStore = useNavigationStore();

const carousels = [];

const duplicateCarouselItems = (carousel) => {
    const items = Array.from(carousel.children);

    if (items.length === 0) return;

    for (let i = 0; i < 1; i++) {
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
        </div>

        <div class="homepage-panel-wrapper accent-light">
            <img class="popout" src="/img/watermelon.svg" alt="watermelon"/>

            <div class="feature-panel-content" style="text-align: center">
                <div class="feature-panel-title">language is a web</div>
                <div class="feature-panel-subtitle">PalWeb is the Web of Palestinian Arabic</div>
            </div>

            <div class="feature-panel inline">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">hypertext dictionary</div>
                    <div class="feature-panel-subtitle">Go where your curiosity takes you.</div>
                    <div class="feature-panel-description">Highly detailed entries are just the beginning: hear
                        everything out loud; see all their forms & conjugations; browse synonyms & antonyms — & more!
                        Sentences show you Terms in their context, too. Click on any Term to jump to its page in an
                        instant!
                    </div>
                </div>
                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/demo-01.mov">
                    </video>
                </div>
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

            <div class="feature-panel inline reverse">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">find anything</div>
                    <div class="feature-panel-subtitle">Just call the Search Genie.</div>
                    <div class="feature-panel-description">Search in Arabic or English & narrow down your search with a
                        variety of filters. Want to find a feminine noun in the Form 1 Active Participle pattern & a
                        CaCāCic broken plural?
                        ثواني!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/demo-02.mov">
                    </video>
                </div>
            </div>
        </div>

        <div class="homepage-panel-wrapper pastel-light">
            <div class="feature-panel-content" style="text-align: center">
                <div class="feature-panel-title">make arabic yours</div>
                <div class="feature-panel-subtitle">hassle-free language learning is here</div>
            </div>

            <div class="feature-panel inline">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">build decks</div>
                    <div class="feature-panel-subtitle">Build your vocabulary, Deck by Deck.</div>
                    <div class="feature-panel-description">Say goodbye to the busy work of piecing together your own
                        vocabulary sets. Use the Deck Master to build your own Decks in a flash, or browse the Deck
                        Library to see all the Decks others have made — & even copy a Deck to put your own spin on
                        someone else's idea!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/demo-03.mov">
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
                    <div class="feature-panel-title">study decks</div>
                    <div class="feature-panel-subtitle">Flashy new ways to practice.</div>
                    <div class="feature-panel-description">Tired of micro-managing third-party flashcard applications?
                        Study your Deck right here with the Deck Master, which gives you total flexibility to adjust how
                        you view your Deck with just one click.
                    </div>
                </div>
                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/demo-04.mov">
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

            <div class="carousel-wrapper">
                <div class="carousel-track">
                    <UserScorecard v-for="user in users" :user="user" :key="'user-carousel' + user.id" :scores="false"/>
                </div>
            </div>

            <div class="feature-panel">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">building community</div>
                    <div class="feature-panel-subtitle">Connect & share with others.</div>
                </div>
                <div class="feature-panel-feature">
                    <div class="window-container">
                        <div class="window-header">
                            <Link :href="route('users.index')" class="material-symbols-rounded">home</Link>
                            <div class="material-symbols-rounded">public</div>
                            <div class="window-header-url">www.palweb.app/hub/users/{user}</div>
                        </div>
                        <div class="window-section-head">
                            <h1>profile</h1>
                        </div>
                        <UserItem :user="featuredUser" size="l" comment tags/>
                    </div>
                </div>
            </div>

            <div class="feature-panel inline reverse">
                <div class="feature-panel-content">
                    <div class="feature-panel-title">record wizard</div>
                    <div class="feature-panel-subtitle">Let your voice shine through.</div>
                    <div class="feature-panel-description">Originally developed to source pronunciation samples from
                        native speakers, the Record Wizard will be open for all to contribute audios to the site.
                        Fluency level is indicated for each Speaker, so don't be shy — every word can have as many
                        audios as there are Arabic speakers in the world!
                    </div>
                </div>

                <div class="feature-panel-feature">
                    <video autoplay muted loop>
                        <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/rw-demo.mov">
                    </video>
                </div>
            </div>

            <div class="portal-button-wrapper">
                <div class="portal-button-head">
                    what will you create?
                </div>
                <div class="portal-button-body">
                    <button @click="NavigationStore.showSignUp = true" class="portal-button">Join PalWeb!</button>
                </div>
            </div>
        </div>
    </div>
</template>
