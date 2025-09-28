<script setup>
import Layout from "../Shared/Layout.vue";
import {route} from "ziggy-js";
import {nextTick, onBeforeUnmount, onMounted, ref} from "vue";
import DeckFlashcard from "../components/DeckFlashcard.vue";
import UserItem from "../components/UserItem.vue";
import HomepageHero from "../components/HomepageHero.vue";
import {useNavigationStore} from "../stores/NavigationStore.js";
import UserScorecard from "../components/UserScorecard.vue";
import Testimonial from "../components/Testimonial.vue";
import RotatingWordColumn from "../components/RotatingWordColumn.vue";
import TermFlashcard from "./Workbench/DeckMaster/UI/TermFlashcard.vue";
import ToggleSingle from "../components/ToggleSingle.vue";
import {useSearchStore} from "../stores/SearchStore.js";
import SentenceItem from "../components/SentenceItem.vue";
import AppButton from "../components/AppButton.vue";
import {useUserStore} from "../stores/UserStore.js";
import {useNotificationStore} from "../stores/NotificationStore.js";

defineProps({
    count: Object,
    users: Array,
    decks: Array,
    sentences: Array,
    testimonials: Array,
    featuredTerm: Object,
    featuredUser: Object,
    featuredDeck: Object,
});

const NotificationStore = useNotificationStore();
const NavigationStore = useNavigationStore();
const SearchStore = useSearchStore();
const UserStore = useUserStore();

const showSignUp = () => {
    if (!UserStore.user) {
        NavigationStore.showSignUp = true

    } else {
        NotificationStore.addNotification('You\'re already signed in!', 'info')
    }
}

const showTranslit = ref(false);
const flipDefault = ref(false);

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

let intervalId = null;

onMounted(async () => {
    await nextTick();
    carousels.length = 0;
    carousels.push(...document.querySelectorAll(".carousel-track"));
    carousels.forEach((carousel) => duplicateCarouselItems(carousel));

    intervalId = setInterval(() => {
        flipDefault.value = !flipDefault.value;
    }, 2000);
});

onBeforeUnmount(() => {
    carousels.forEach((carousel) => {
        carousel.innerHTML = "";
    });

    clearInterval(intervalId);
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

        <div class="homepage-section accent-light" style="padding-block-end: 25.6rem">
            <div class="homepage-panel-content">
                <div class="feature-panel-subtitle"
                     style="margin-block: 3.2rem; display: flex; flex-flow: row wrap; align-items: center; justify-content: center; gap: 1.2rem 2.4rem">
                    so, you’re
                    <div style="display: flex; flex-flow: row wrap; gap: 1.6rem">
                        <RotatingWordColumn :words="['learning', 'teaching', 'researching']"/>
                        <RotatingWordColumn :words="['Spoken', 'Levantine', 'Palestinian', 'Jordanian']"/>
                    </div>
                    Arabic
                </div>
                <div class="feature-panel-title">PalWeb is your hub</div>
            </div>

            <div class="carousel-wrapper">
                <div class="carousel-track">
                    <UserScorecard v-for="user in users" :user="user" :key="'user-carousel' + user.id" :scores="false"/>
                </div>
            </div>
        </div>

        <div class="homepage-section pastel-light">
            <div class="homepage-panel-content faq-panel" style="padding-block-end: 3.2rem;">
                <div class="feature-panel-title">everyone get in</div>
                <div class="feature-panel-subtitle">We’re bringing learners, educators & speakers together.</div>
                <div class="feature-panel-description">Whether you're just starting to learn Arabic or you're a
                    native speaker interested in language preservation, PalWeb's suite of database-powered learning
                    & documentation tools bring everyone together to celebrate Palestinian Arabic.
                </div>

                <div class="portal-button-wrapper" style="margin-block: 9.6rem 6.4rem">
                    <div class="portal-button-head">
                        keep the convo going
                    </div>
                    <div class="portal-button-body">
                        <a class="portal-button" href="https://discord.gg/3Wf7Q6RCjV" target="_blank">Join Our
                            Discord!</a>
                    </div>
                </div>
            </div>


            <!--            <div-->
            <!--                style="display: flex; flex-flow: row wrap; align-items: flex-start; justify-content: center; gap: 1.6rem 6.4rem">-->
            <!--                <div class="feature-panel inline" style="grid-template-columns: 1fr">-->
            <!--                    <div class="homepage-panel-content">-->
            <!--                        <div class="feature-panel-title">Learn</div>-->
            <!--                        <div class="feature-panel-subtitle">Arabic, easier than you think.</div>-->
            <!--                        <div class="feature-panel-description">-->
            <!--                            (A suite of reference & study tools.)-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <div class="feature-panel inline" style="grid-template-columns: 1fr">-->
            <!--                    <div class="homepage-panel-content">-->
            <!--                        <div class="feature-panel-title">Teach</div>-->
            <!--                        <div class="feature-panel-subtitle">Resources at your fingertips.</div>-->
            <!--                        <div class="feature-panel-description">-->
            <!--                            (A suite of reference & study tools.)-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <div class="feature-panel inline" style="grid-template-columns: 1fr">-->
            <!--                    <div class="homepage-panel-content">-->
            <!--                        <div class="feature-panel-title">Research</div>-->
            <!--                        <div class="feature-panel-subtitle">Open-source documentation tools.</div>-->
            <!--                        <div class="feature-panel-description">-->
            <!--                            (A suite of reference & study tools.)-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>

        <div class="homepage-section pastel-light" style="padding-block-end: 25.6rem">
            <div>
                <img class="world" src="/img/watermelon.svg" alt="watermelon"/>
            </div>

            <div class="homepage-panel-content">
                <div class="feature-panel-title">language is a web</div>
                <div class="feature-panel-subtitle">PalWeb is the Web of Palestinian Arabic</div>
            </div>

            <div class="homepage-panel-wrapper inline">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">hypertext dictionary</div>
                    <div class="feature-panel-subtitle">Go where your curiosity takes you.</div>
                    <div class="feature-panel-description">Highly detailed entries are just the beginning: hear
                        everything out loud; jump between synonyms & antonyms; browse Sentences to see Terms in their
                        context — & more!
                    </div>
                    <div class="feature-preview" style="margin-block-start: 6.4rem">
                        <div class="model-list">
                            <SentenceItem v-for="sentence in sentences" :model="sentence" :key="sentence.id"/>
                        </div>
                    </div>
                </div>
                <div class="homepage-panel-content">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front01.png" alt="Front Page 01">
                </div>
            </div>

            <div class="homepage-panel-wrapper inline reverse">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">find anything</div>
                    <div class="feature-panel-subtitle">Just call the Search Genie.</div>
                    <div class="feature-panel-description">Search in Arabic or English & narrow down your search with a
                        variety of filters tailored for the Arabic root-pattern system. Pin things to find easily later!
                    </div>
                    <div class="feature-preview" style="margin-block: 3.2rem; justify-self: center">
                        <AppButton label="Try Me" @click="SearchStore.openSearchGenie"/>
                    </div>
                    <div class="model-counter-wrapper" style="margin-block-start: 3.2rem">
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
                </div>

                <div class="homepage-panel-content">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front02.png" alt="Front Page 02">
                </div>
            </div>

            <!--            <div class="homepage-panel-wrapper inline">-->
            <!--                <div class="homepage-panel-content">-->
            <!--                    <div class="feature-panel-title">wiki & docs</div>-->
            <!--                    <div class="feature-panel-subtitle">PalWeb, the free encyclopedia.</div>-->
            <!--                    <div class="feature-panel-description">The PalWeb Wiki includes a descriptive grammar of Spoken-->
            <!--                        Palestinian Arabic. Are you an Arabic philologist or dialectologist? Help us fill it out!-->
            <!--                    </div>-->
            <!--                </div>-->

            <!--                <div class="homepage-panel-content">-->
            <!--                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front05.png" alt="Front Page 05">-->
            <!--                </div>-->
            <!--            </div>-->

            <div class="homepage-panel-wrapper inline">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">record wizard</div>
                    <div class="feature-panel-subtitle">Let your voice shine through.</div>
                    <div class="feature-panel-description">Breathe life into language by recording pronunciation samples
                        of everything in your dialect — & represent the diversity of Palestinian Arabic.
                    </div>
                </div>

                <div class="homepage-panel-content">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front05.png" alt="Front Page 05">
                </div>
            </div>
        </div>

        <div class="homepage-section accent-light">
            <div class="homepage-panel-content faq-panel">
                <div class="feature-panel-title">stairway to Arabic</div>
                <div class="feature-panel-subtitle">Why Spoken Arabic?</div>
                <div class="feature-panel-description"><b>If you really want to learn Arabic, start with Spoken
                    Arabic.</b> Standard Arabic is hard, even for native Arabic speakers. Learning Spoken Arabic
                    first mirrors how native speakers acquire language — socially & contextually — & makes
                    transitioning to Standard Arabic not only easier but more meaningful, as you’ll already have an
                    intuitive feel for structure, sound, and flow.
                </div>
                <div class="feature-panel-subtitle">Why Palestinian Arabic?</div>
                <div class="feature-panel-description">Palestinian Arabic is a form of Levantine Arabic, one of the
                    most widely understood dialect families in the Arab world, <b>spoken by up to 40 million
                        people</b> in Palestine, Jordan, Lebanon, Syria & beyond. It’s one of the more conservative
                    dialects of Spoken Arabic, with strong similarities to Standard Arabic (up to 80% lexical
                    overlap!), making it <b>a perfect starting point</b> for learners to build a solid foundation in
                    Arabic vocabulary & grammar.
                </div>
            </div>
        </div>

        <div class="homepage-section accent-light">
            <div>
                <img class="world" src="/img/key.svg" alt="Key"/>
            </div>

            <div class="homepage-panel-content">
                <div class="feature-panel-title">make arabic yours</div>
                <div class="feature-panel-subtitle">hassle-free language learning is here</div>
            </div>

            <div class="homepage-panel-wrapper inline reverse">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">deck builder</div>
                    <div class="feature-panel-subtitle">Build your vocabulary, Deck by Deck.</div>
                    <div class="feature-panel-description">Say goodbye to the busy work of piecing together your own
                        vocabulary sets & micro-managing flashcard applications. Use the Deck Master to build your own
                        Decks in a flash, then study them with total flexibility to adjust how you view them with just
                        one click. Browse the Library to see Decks others have made!
                    </div>
                    <div class="feature-preview" style="margin-block: 3.2rem">
                        <ToggleSingle v-model="showTranslit" label="Show Transcription"/>
                        <TermFlashcard
                            :model="featuredTerm.data"
                            :showTranslit="showTranslit"
                            :flipDefault="flipDefault"
                        />
                    </div>
                </div>

                <div class="homepage-panel-content">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front03.png" alt="Front Page 03">
                </div>
            </div>

            <div class="carousel-wrapper">
                <div class="carousel-track" style="animation-direction: reverse">
                    <DeckFlashcard v-for="deck in decks" :model="deck" :key="'deck-carousel' + deck.id"/>
                </div>
            </div>

            <div class="homepage-panel-wrapper">
                <div class="homepage-panel-content" style="max-width: 96rem; justify-self: center">
                    <div class="feature-panel-title">quiz & score</div>
                    <div class="feature-panel-subtitle">Test yourself. Best yourself.</div>
                    <div class="feature-panel-description">Drill your flashcard Decks with customizable Quizzes that
                        will put your knowledge of Arabic vocabulary to the test. Save your Scores & see how your
                        learning journey evolves over time!
                    </div>
                </div>
                <div class="homepage-panel-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.6rem">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front-quizzer01.png"
                         alt="Quizzer 01">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front-quizzer02.png"
                         alt="Quizzer 02">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front-quizzer03.png"
                         alt="Quizzer 03">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front-quizzer04.png"
                         alt="Quizzer 04">
                </div>
            </div>
        </div>

        <div class="homepage-section pastel-light">
            <div>
                <img src="/img/globe-america.svg" class="world" alt="America"/>
                <img src="/img/globe-africa.svg" class="world" alt="Africa"/>
                <img src="/img/globe-asia.svg" class="world" alt="Asia"/>
            </div>

            <div class="homepage-panel-content" style="text-align: center">
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
                <div class="carousel-track" style="animation: carousel-scroll 60s linear infinite">
                    <Testimonial v-for="(testimonial, index) in testimonials" :testimonial="testimonial" :key="index"/>
                </div>
            </div>

            <div class="homepage-panel-wrapper" style="max-width: 96rem">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">building community</div>
                    <div class="feature-panel-subtitle">Connect & share with others.</div>
                </div>
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

            <div class="portal-button-wrapper">
                <div class="portal-button-head">
                    what are you waiting for?
                </div>
                <div class="portal-button-body">
                    <button @click="showSignUp" class="portal-button">Sign Me Up!</button>
                </div>
            </div>
        </div>
    </div>
</template>
