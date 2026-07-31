<script setup>
import Layout from "../Shared/Layout.vue";
import {route} from "ziggy-js";
import {computed, nextTick, onBeforeUnmount, onMounted, onUnmounted, ref} from "vue";
import DeckFlashcard from "../components/DeckFlashcard.vue";
import UserItem from "../components/UserItem.vue";
import HomepageHero from "../components/HomepageHero.vue";
import {useNavigationStore} from "../stores/NavigationStore.js";
import UserScorecard from "../components/UserScorecard.vue";
import RotatingWordColumn from "../components/RotatingWordColumn.vue";
import TermFlashcard from "./Workbench/DeckMaster/UI/TermFlashcard.vue";
import ToggleSingle from "../components/ToggleSingle.vue";
import {useSearchStore} from "../stores/SearchStore.js";
import SentenceItem from "../components/SentenceItem.vue";
import AppButton from "../components/AppButton.vue";
import {useUserStore} from "../stores/UserStore.js";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {Carousel, Pagination, Slide} from "vue3-carousel";
import Kufiyye from "../Shared/Backgrounds/Kufiyye.vue";
import CommentItem from "../components/CommentItem.vue";
import InfiniteCarousel from "../components/InfiniteCarousel.vue";
import {useI18n} from "vue-i18n";

const {t} = useI18n();

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
        NotificationStore.addNotification(t('pages.home.notifications.signed-in'), 'info')
    }
}

const showTranslit = ref(false);
const flipDefault = ref(false);

const profileActions = computed(() =>
    ['learning', 'teaching', 'researching'].map((action) =>
        t(`pages.home.titles.palweb.actions.${action}`)
    )
);

const profileDialects = computed(() =>
    ['spoken', 'levantine', 'palestinian', 'jordanian'].map((dialect) =>
        t(`pages.home.titles.palweb.dialects.${dialect}`)
    )
);

let intervalId = null;

onMounted(async () => {
    await nextTick();

    intervalId = setInterval(() => {
        flipDefault.value = !flipDefault.value;
    }, 2000);

    window.addEventListener('scroll', handleScroll, {passive: true})
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})

const heroOffset = ref(0);
let ticking = false;

const handleScroll = () => {
    if (!ticking) {
        requestAnimationFrame(() => {
            const scrollY = window.scrollY
            heroOffset.value = scrollY * 0.5
            ticking = false
        })
        ticking = true
    }
}

onBeforeUnmount(() => {
    clearInterval(intervalId);
});

defineOptions({
    layout: Layout
});
</script>
<template>
    <Head title="Home"/>
    <div class="homepage-head-container">
        <div class="kufiyye-strip">
            <Kufiyye v-for="n in 4" :key="n" class="kufiyye-tile"/>
        </div>
        <div class="homepage-hero-wrapper"
             :style="{ transform: `translateY(${heroOffset}px)` }"
        >
            <Link :href="route('wiki.show', 'release-notes')" class="feature-callout">{{ $t('pages.home.release-notes', {version: 'v2.4'}) }}</Link>
            <HomepageHero/>
        </div>
    </div>
    <div id="app-body" class="homepage">
        <div class="homepage-section accent-light" style="padding-block-end: 25.6rem">
            <div class="homepage-panel-content">
                <i18n-t
                    keypath="pages.home.titles.palweb.title"
                    tag="div"
                    class="feature-panel-subtitle"
                >
                    <template #action>
                        <RotatingWordColumn :words="profileActions" />
                    </template>
                    <template #dialect>
                        <RotatingWordColumn :words="profileDialects" />
                    </template>
                </i18n-t>
                <div class="feature-panel-title">{{ $t('pages.home.titles.palweb.subtitle') }}</div>
            </div>

            <InfiniteCarousel>
                <UserScorecard v-for="user in users" :user="user" :key="'user-carousel-' + user.id" :scores="false"/>
            </InfiniteCarousel>
        </div>

        <div class="homepage-section pastel-light">
            <div class="homepage-panel-content faq-panel" style="padding-block-end: 3.2rem;">
                <div class="feature-panel-title">{{ $t('pages.home.windows.together.title') }}</div>
                <div class="feature-panel-subtitle">{{ $t('pages.home.windows.together.subtitle') }}</div>
                <div class="feature-panel-description">{{ $t('pages.home.windows.together.description') }}</div>

                <div class="portal-button-wrapper" style="margin-block: 9.6rem 6.4rem">
                    <div class="portal-button-head">
                        {{ $t('pages.home.prompts.join-discord.message') }}
                    </div>
                    <div class="portal-button-body">
                        <a class="portal-button" href="https://discord.gg/3Wf7Q6RCjV" target="_blank">
                            {{ $t('pages.home.prompts.join-discord.button') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="homepage-section pastel-light" style="padding-block-end: 25.6rem">
            <div>
                <img class="world" src="/img/watermelon.svg" alt="watermelon"/>
            </div>

            <div class="homepage-panel-content">
                <div class="feature-panel-title">{{ $t('pages.home.titles.language.title') }}</div>
                <div class="feature-panel-subtitle">{{ $t('pages.home.titles.language.subtitle') }}</div>
            </div>

            <div class="homepage-panel-wrapper inline">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">{{ $t('pages.home.features.dictionary.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.dictionary.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.dictionary.description') }}</div>
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
                    <div class="feature-panel-title">{{ $t('pages.home.features.search-genie.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.search-genie.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.search-genie.description') }}</div>
                    <div class="feature-preview" style="margin-block: 3.2rem; justify-self: center">
                        <AppButton :label="$t('pages.home.features.search-genie.try-me')" @click="SearchStore.openSearchGenie"/>
                    </div>
                    <div class="model-counter-wrapper" style="margin-block-start: 3.2rem">
                        <div class="model-counter">
                            <div class="model-counter-count">{{ count.terms }}</div>
                            <div class="model-counter-body">
                                <span class="model-counter-model">{{ $t('models.terms') }}</span>
                            </div>
                        </div>
                        <div class="model-counter">
                            <div class="model-counter-count">{{ count.sentences }}</div>
                            <div class="model-counter-body">
                                <span class="model-counter-model">{{ $t('models.sentences') }}</span>
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
                    <div class="feature-panel-title">{{ $t('pages.home.features.sound-booth.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.sound-booth.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.sound-booth.description') }}</div>
                </div>

                <div class="homepage-panel-content">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front04.png" alt="Front Page 04">
                </div>
            </div>
        </div>

        <div class="homepage-section accent-light">
            <div class="homepage-panel-content faq-panel">
                <div class="feature-panel-title">{{ $t('pages.home.windows.arabic.title') }}</div>
                <div class="feature-panel-subtitle">{{ $t('pages.home.windows.arabic.subtitle-1') }}</div>
                <i18n-t
                    tag="div"
                    class="feature-panel-description"
                    keypath="pages.home.windows.arabic.description-1"
                >
                    <template #highlight>
                        <strong>{{ $t('pages.home.windows.arabic.description-1-highlight') }}</strong>
                    </template>
                </i18n-t>
                <div class="feature-panel-subtitle">{{ $t('pages.home.windows.arabic.subtitle-2') }}</div>
                <i18n-t
                    tag="div"
                    class="feature-panel-description"
                    keypath="pages.home.windows.arabic.description-2"
                >
                    <template #highlight-1>
                        <strong>{{ $t('pages.home.windows.arabic.description-2-highlight-1') }}</strong>
                    </template>
                    <template #highlight-2>
                        <strong>{{ $t('pages.home.windows.arabic.description-2-highlight-2') }}</strong>
                    </template>
                </i18n-t>
            </div>
        </div>

        <div class="homepage-section accent-light">
            <div>
                <img class="world" src="/img/key.svg" alt="Key"/>
            </div>

            <div class="homepage-panel-content">
                <div class="feature-panel-title">{{ $t('pages.home.titles.arabic.title') }}</div>
                <div class="feature-panel-subtitle">{{ $t('pages.home.titles.arabic.subtitle') }}</div>
            </div>

            <div class="homepage-panel-wrapper inline reverse">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">{{ $t('pages.home.features.build-decks.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.build-decks.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.build-decks.description') }}</div>
                    <div class="feature-preview" style="margin-block: 3.2rem">
                        <ToggleSingle v-model="showTranslit" :label="$t('components.common.options.show-transcription')"/>
                        <TermFlashcard v-if="featuredTerm"
                                       :model="featuredTerm"
                                       :showTranslit="showTranslit"
                                       :flipDefault="flipDefault"
                        />
                    </div>
                </div>

                <div class="homepage-panel-content">
                    <img src="https://abdulbaha.fra1.digitaloceanspaces.com/images/front03.png" alt="Front Page 03">
                </div>
            </div>

            <div class="homepage-panel-wrapper inline">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">{{ $t('pages.home.features.card-dealer.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.card-dealer.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.card-dealer.description') }}</div>
                </div>

                <Carousel
                    :autoplay="3200"
                    :items-to-show="1"
                    :wrap-around="true"
                >
                    <template #slides>
                        <Slide v-for="slide in 4" :key="slide">
                            <img
                                :src="`https://abdulbaha.fra1.digitaloceanspaces.com/images/front-card-dealer0${slide}.png`"
                                alt="Slide Image">
                        </Slide>
                    </template>
                    <template #addons>
                        <Pagination/>
                    </template>
                </Carousel>
            </div>

            <InfiniteCarousel direction="right">
                <DeckFlashcard v-for="deck in decks" :model="deck" :key="'deck-carousel-' + deck.id"/>
            </InfiniteCarousel>

            <div class="homepage-panel-wrapper inline">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">{{ $t('pages.home.features.study-decks.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.study-decks.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.study-decks.description') }}</div>
                </div>
                <Carousel
                    :autoplay="3200"
                    :items-to-show="1"
                    :wrap-around="true"
                >
                    <template #slides>
                        <Slide v-for="slide in 4" :key="slide">
                            <img
                                :src="`https://abdulbaha.fra1.digitaloceanspaces.com/images/front-quizzer0${slide}.png`"
                                alt="Slide Image">
                        </Slide>
                    </template>
                    <template #addons>
                        <Pagination/>
                    </template>
                </Carousel>
            </div>
            <div class="homepage-panel-wrapper inline reverse">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">{{ $t('pages.home.features.academy.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.academy.subtitle') }}</div>
                    <div class="feature-panel-description">{{ $t('pages.home.features.academy.description') }}</div>
                </div>
                <Carousel
                    :autoplay="2800"
                    :items-to-show="1"
                    :wrap-around="true"
                >
                    <template #slides>
                        <Slide v-for="slide in 3" :key="slide">
                            <img
                                :src="`https://abdulbaha.fra1.digitaloceanspaces.com/images/front-academy0${slide}.png`"
                                alt="Slide Image">
                        </Slide>
                    </template>
                    <template #addons>
                        <Pagination/>
                    </template>
                </Carousel>
            </div>
        </div>

        <div class="homepage-section pastel-light">
            <div>
                <img src="/img/globe-america.svg" class="world" alt="America"/>
                <img src="/img/globe-africa.svg" class="world" alt="Africa"/>
                <img src="/img/globe-asia.svg" class="world" alt="Asia"/>
            </div>

            <div class="homepage-panel-content" style="text-align: center">
                <div class="feature-panel-title">{{ $t('pages.home.titles.community.title') }}</div>
                <div class="feature-panel-subtitle">{{ $t('pages.home.titles.community.subtitle') }}</div>
            </div>

            <div class="model-counter-wrapper">
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.decks }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">{{ $t('models.decks') }}</span>
                    </div>
                </div>
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.audios }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">{{ $t('models.audios') }}</span>
                    </div>
                </div>
                <div class="model-counter">
                    <div class="model-counter-count">{{ count.users }}</div>
                    <div class="model-counter-body">
                        <span class="model-counter-model">{{ $t('models.users') }}</span>
                    </div>
                </div>
            </div>

            <InfiniteCarousel gap="6.4rem">
                <CommentItem v-for="(testimonial, index) in testimonials" :model="testimonial"
                             :key="'testimonial-carousel-' + index" class="comment-testimonial"/>
            </InfiniteCarousel>

            <div class="homepage-panel-wrapper" style="max-width: 96rem">
                <div class="homepage-panel-content">
                    <div class="feature-panel-title">{{ $t('pages.home.features.community.title') }}</div>
                    <div class="feature-panel-subtitle">{{ $t('pages.home.features.community.subtitle') }}</div>
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
                    <UserItem v-if="featuredUser" :user="featuredUser" size="l" comment tags/>
                </div>
            </div>

            <div class="portal-button-wrapper">
                <div class="portal-button-head">
                    {{ $t('pages.home.prompts.sign-up.message') }}
                </div>
                <div class="portal-button-body">
                    <button @click="showSignUp" class="portal-button">
                        {{ $t('pages.home.prompts.sign-up.button') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.homepage-section {
    width: 100%;
    display: grid;
    gap: 6.4rem;
    justify-items: center;
    padding: 6.4rem 3.2rem;
    position: relative;

    & > .popout {
        position: absolute;

        // Mobile
        width: 6.4rem;
        top: -3.2rem;
        right: 5%;
    }


    div:has(.world) {
        display: flex;
        gap: 1.6rem;
        position: absolute;
        top: -3.2rem;

        .world {
            width: 6.4rem;
            transition: 0.2s cubic-bezier(.68, -0.55, .27, 1.55);

            &:hover {
                transform: scale(1.05) rotate(-3deg);
            }
        }
    }

    &.pastel-light {
        background: var(--color-pastel-light);
    }

    &.accent-light {
        background: var(--color-accent-light);
    }

    @media (width >= 960px) {
        gap: 12.8rem;
        padding: 12.8rem 6.4rem 12.8rem;

        & > .popout {
            width: 9.6rem;
            top: -4.8rem;
            right: 10%;
        }
    }
}

.homepage-panel-wrapper {
    width: min(100%, 128rem);
    display: grid;
    gap: 4.8rem 6.4rem;

    &.inline {
        grid-template-columns: auto;
        max-width: 128rem;

        @media (width >= 960px) {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    &.reverse {
        direction: rtl;
    }

    .homepage-panel-content {
        width: 100%;
        text-align: start;
        font-size: clamp(3.6rem, 8vw, 4.8rem);

        & > video, & > img {
            width: 100%;
            border-radius: 0.25em;
        }
    }
}

.homepage-panel-content {
    display: grid;
    gap: 0.8rem;
    align-content: start;
    text-align: center;
    position: relative;
    direction: ltr;
    font-size: clamp(5.6rem, 10vw, 6.4rem);

    .feature-panel-title {
        color: var(--color-dark-primary);
        font-family: var(--display-font);
        font-size: 1em;
        line-height: 1em;
        text-transform: uppercase;
        padding-block-end: 0.0625em;
        hyphens: none;
    }

    .feature-panel-subtitle {
        font-family: var(--head-font);
        font-size: 0.5em;
        font-weight: 700;
        line-height: 1.2em;
        color: var(--color-medium-primary);
        padding-block-start: 0.125em;
        hyphens: none;
    }

    .feature-panel-description {
        font-family: var(--body-font);
        font-size: 0.4em;
        line-height: 1.75;
        margin-block-start: 0.5em;
    }

    .feature-preview {
        display: none;
    }

    @media (width >= 960px) {
        .feature-preview {
            display: block;
        }
    }

    .app-button {
        font-size: 2.4rem;
    }
}

.homepage-section:has(.faq-panel) {
    padding: 0;
}

.faq-panel {
    margin-block-start: -12.8rem;
    width: min(100%, 96rem);
    background: white;
    text-align: start;
    padding: 6.4rem 6.4rem 9.6rem;

    .feature-panel-title {
        text-align: center;
    }

    .feature-panel-subtitle {
        font-size: 0.4em;
        margin-block-start: 2em;
    }

    .feature-panel-description {
        font-family: var(--body-font);
        font-size: 0.3em;
    }

    @media (width >= 960px) {
        border-radius: 1.6rem;
        padding: 6.4rem 9.6rem 12.8rem;
    }
}

.model-counter-wrapper {
    display: flex;
    flex-flow: row wrap;
    justify-content: center;
    font-size: clamp(6rem, 12vw, 8rem);
    gap: 0.25em 0.5em;

    @media (width >= 960px) {
        gap: 1.6rem 9.6rem
    }
}

.model-counter {
    display: flex;
    flex-flow: column;
    align-items: center;
    justify-content: center;
    height: 2.75em;
    width: 2.75em;
    border-radius: 50%;
    background: white;
    user-select: none;
    transition: 0.1s ease-in-out;

    &:hover {
        translate: 0.1em -0.1em;
        filter: drop-shadow(-0.1em 0.1em 0 var(--color-pastel-light));
    }

    .model-counter-count {
        color: var(--color-dark-primary);
        font-family: var(--display-font);
        font-size: 1em;
        line-height: 0.75;
    }

    .model-counter-body {
        display: grid;
        justify-items: center;

        .model-counter-model {
            color: var(--color-medium-primary);
            font-family: var(--display-font);
            font-size: 0.5em;
            line-height: 1.25;
            hyphens: none;
        }
    }
}

.homepage-head-container {
    display: grid;
    justify-items: center;
    width: 100%;
    min-height: 100vh;
    overflow: hidden;
    background: var(--color-accent-medium);
}

.homepage-hero-wrapper {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 96rem;
    display: grid;
    gap: 6.4rem;
    align-content: center;
    justify-items: center;
    margin-block: 6.4rem 12.8rem;
    will-change: transform;
}

.kufiyye-strip {
    position: fixed;
    inset-inline: 0;
    top: -6.4rem;
    rotate: 180deg;
    width: 100%;
    height: auto;
    z-index: 0;
    pointer-events: none;
    display: flex;
    overflow: hidden;
    align-items: flex-end;
    opacity: 0.3;
}

.kufiyye-tile {
    flex: 0 0 96rem;
    width: 96rem;
    max-width: 96rem;
}
</style>
