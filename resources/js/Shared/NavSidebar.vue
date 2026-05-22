<script setup>
import {useUserStore} from "../stores/UserStore.js";
import {useNavigationStore} from "../stores/NavigationStore.js";
import {router} from '@inertiajs/vue3'
import {route} from 'ziggy-js';
import {onMounted, onUnmounted, ref} from "vue";
import {Carousel, Slide} from "vue3-carousel";
import NavAuth from "./NavAuth.vue";
import SignIn from "../components/Modals/SignIn.vue";
import SignUp from "../components/Modals/SignUp.vue";
import ModalWrapper from "../components/Modals/ModalWrapper.vue";
import SendFeedback from "../components/Modals/SendFeedback.vue";
import SendMail from "../components/Modals/SendMail.vue";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

const carouselRef = ref(null);
const sidebarRef = ref(null);

const showSendMail = ref(false);

const zIndices = ref({
    home: -1,
    academy: -1,
    library: -1,
    workbench: -1,
    office: -1,
});

const toSection = async (section, key) => {
    NavigationStore.data.section = section;
    carouselRef.value?.slideTo(key);
}

const navigateOrPrompt = (page) => {
    if (UserStore.isUser) {
        router.get(route(page));

    } else {
        NavigationStore.showSignIn = true;
    }
}

const onSlideStart = () => {
    if (NavigationStore.data.section !== 'home') {
        Object.keys(zIndices.value).forEach((sectionKey) => {
            zIndices.value[sectionKey] = -1;
        });
        zIndices.value[NavigationStore.data.section] = 1;
    }
}

const onSlideEnd = () => {
    if (NavigationStore.data.section === 'home') {
        Object.keys(zIndices.value).forEach((sectionKey) => {
            zIndices.value[sectionKey] = -1;
        });
    }
}

const handleClickOutside = (event) => {
    if (NavigationStore.data.isOpen && !sidebarRef.value.contains(event.target)) {
        NavigationStore.closeSidebar();
    }
};

onMounted(() => {
    const removeNavigationListener = router.on('navigate', () => {
        NavigationStore.closeSidebar();
    });
    document.addEventListener('click', handleClickOutside);

    onUnmounted(() => {
        removeNavigationListener();
        document.removeEventListener('click', handleClickOutside);
    });
});


</script>
<template>
    <div class="nav-sidebar-container" :class="{ 'show': NavigationStore.data.isOpen }">
        <div class="nav-sidebar" ref="sidebarRef">
            <div class="nav-sidebar-head">
                <div>
                    {{ $t(UserStore.user ? UserStore.highestRole : 'guest') }}
                    <Link v-if="UserStore.user" class="auth-role" :href="route('subscription.index')">
                        {{ $t('user.subscriptions.manage') }}
                    </Link>
                </div>
                <template v-if="UserStore.isUser">
                    <button class="material-symbols-rounded" @click="router.post(route('signout'))">logout</button>
                </template>
                <template v-else>
                    <button class="material-symbols-rounded" @click="NavigationStore.showSignUp = true">person_add
                    </button>
                    <button class="material-symbols-rounded" @click="NavigationStore.showSignIn = true">login
                    </button>
                </template>
                <button class="material-symbols-rounded" @click="NavigationStore.closeSidebar">close</button>
            </div>
            <NavAuth v-if="UserStore.user"/>
            <div class="nav-sidebar-body">
                <div class="nav-carousel-wrapper">
                    <div class="nav-carousel-head">
                        <button v-if="NavigationStore.data.section !== 'home'" @click.stop="toSection('home', 0)"><-
                        </button>
                        <div>{{ $t('nav.sidebar.' + NavigationStore.data.section + '.title') }}</div>
                    </div>
                    <Carousel
                        :items-to-show="1"
                        ref="carouselRef"
                        @slide-start="onSlideStart"
                        @slide-end="onSlideEnd"
                    >
                        <Slide key="0">
                            <div @click="toSection('academy', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'academy' }">
                                <div>{{ $t('nav.sidebar.academy.title') }}</div>
                                <div>{{ $t('nav.sidebar.academy.subtitle') }}</div>
                            </div>
                            <div @click="toSection('library', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'library' }">
                                <div>{{ $t('nav.sidebar.library.title') }}</div>
                                <div>{{ $t('nav.sidebar.library.subtitle') }}</div>
                            </div>
                            <div @click="toSection('workbench', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'workbench' }">
                                <div>{{ $t('nav.sidebar.workbench.title') }}</div>
                                <div>{{ $t('nav.sidebar.workbench.subtitle') }}</div>
                            </div>
                            <div v-if="['admin'].includes(UserStore.highestRole)"
                                 @click="toSection('office', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'office' }">
                                <div>{{ $t('nav.sidebar.office.title') }}</div>
                                <div>{{ $t('nav.sidebar.office.subtitle') }}</div>
                            </div>
                        </Slide>
                        <Slide class="nav-carousel-slide" key="1">
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.academy }">
                                <div @click="navigateOrPrompt('units.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                        'active': ['Academy/Units/Index', 'Academy/Units/Show', 'Academy/Lessons/Show'].includes($page.component),
                                        'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.lessons.title') }}</div>
                                    <div>{{ $t('nav.sidebar.lessons.subtitle') }}</div>
                                </div>
                                <div @click="navigateOrPrompt('dialogs.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Academy/Dialogs/Index', 'Academy/Dialogs/Show'].includes($page.component),
                                         'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.dialogs.title') }}</div>
                                    <div>{{ $t('nav.sidebar.dialogs.subtitle') }}</div>
                                </div>
                                <div @click="navigateOrPrompt('scores.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Academy/Scores/Index', 'Academy/Scores/History'].includes($page.component),
                                         'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.scores.title') }}</div>
                                    <div>{{ $t('nav.sidebar.scores.subtitle') }}</div>
                                </div>
                            </div>
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.library }">
                                <Link :href="route('terms.index')"
                                      class="nav-carousel-page-item"
                                      :class="{ 'active': ['Library/Terms/Index', 'Library/Terms/Show'].includes($page.component) }"
                                >
                                    <div>{{ $t('nav.sidebar.dictionary.title') }}</div>
                                    <div>{{ $t('nav.sidebar.dictionary.subtitle') }}</div>
                                </Link>
                                <Link :href="route('sentences.index')"
                                      class="nav-carousel-page-item"
                                      :class="{ 'active': ['Library/Sentences/Index', 'Library/Sentences/Show'].includes($page.component) }"
                                >
                                    <div>{{ $t('nav.sidebar.corpus.title') }}</div>
                                    <div>{{ $t('nav.sidebar.corpus.subtitle') }}</div>
                                </Link>
                                <div @click="navigateOrPrompt('decks.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Library/Decks/Index', 'Library/Decks/Show'].includes($page.component),
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.decks.title') }}</div>
                                    <div>{{ $t('nav.sidebar.decks.subtitle') }}</div>
                                </div>
                                <div @click="navigateOrPrompt('audios.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Library/Audios/Index', 'Library/Audios/Speaker'].includes($page.component),
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.audios.title') }}</div>
                                    <div>{{ $t('nav.sidebar.audios.subtitle') }}</div>
                                </div>
                            </div>
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.workbench }">
                                <div @click="navigateOrPrompt('deck-master.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Workbench/DeckMaster/Index', 'Workbench/DeckMaster/Build', 'Workbench/DeckMaster/Study'].includes($page.component),
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.deck-master.title') }}</div>
                                    <div>{{ $t('nav.sidebar.deck-master.subtitle') }}</div>
                                </div>
                                <div @click="navigateOrPrompt('card-dealer.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Workbench/CardDealer/Index', 'Workbench/CardDealer/Review'].includes($page.component),
                                         'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.card-dealer.title') }}</div>
                                    <div>{{ $t('nav.sidebar.card-dealer.subtitle') }}</div>
                                </div>
                                <div @click="navigateOrPrompt('sound-booth.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': $page.component === 'Workbench/SoundBooth/SoundBooth',
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>{{ $t('nav.sidebar.sound-booth.title') }}</div>
                                    <div>{{ $t('nav.sidebar.sound-booth.subtitle') }}</div>
                                </div>
                            </div>
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.office }">
                                <div
                                    @click="navigateOrPrompt('word-logger.index')"
                                    class="nav-carousel-page-item"
                                    :class="{ 'active': $page.component === 'Office/WordLogger/Index' }"
                                >
                                    <div>{{ $t('nav.sidebar.word-logger.title') }}</div>
                                    <div>{{ $t('nav.sidebar.word-logger.subtitle') }}</div>
                                </div>
                                <div
                                    @click="navigateOrPrompt('speech-maker.index')"
                                    class="nav-carousel-page-item"
                                    :class="{ 'active': $page.component === 'Office/SpeechMaker/SpeechMaker' }"
                                >
                                    <div>{{ $t('nav.sidebar.speech-maker.title') }}</div>
                                    <div>{{ $t('nav.sidebar.speech-maker.subtitle') }}</div>
                                </div>
                                <div
                                    @click="navigateOrPrompt('lesson-planner.index')"
                                    class="nav-carousel-page-item"
                                    :class="{ 'active': $page.component === 'Office/LessonPlanner/Course' }"
                                >
                                    <div>{{ $t('nav.sidebar.lesson-planner.title') }}</div>
                                    <div>{{ $t('nav.sidebar.lesson-planner.subtitle') }}</div>
                                </div>
                            </div>
                        </Slide>
                    </Carousel>

                    <div class="nav-portal-wrapper">
                        <div @click="navigateOrPrompt('users.index')" class="nav-portal">
                            <img src="/img/globe-africa.svg" alt="Hub"/>
                            <div>hub</div>
                        </div>
                        <Link :href="route('wiki.index')" class="nav-portal">
                            <img src="/img/globe-america.svg" alt="Wiki"/>
                            <div>wiki</div>
                        </Link>
                    </div>
                </div>

                <div v-if="UserStore.isSuperuser" class="nav-user-menu">
                    <div class="nav-user-menu-head">{{ $t('nav.sidebar.my-admin') }}</div>
                    <div class="nav-user-menu-items">
                        <button @click="showSendMail = true">{{ $t('nav.sidebar.send-mail') }}</button>
                        <Link :href="route('feedback.index')">{{ $t('nav.sidebar.view-feedback') }}</Link>

                        <button v-if="!UserStore.isAdmin" @click="router.get(route('admin.toggle-view'))">
                            Restore Admin View
                        </button>
                        <template v-else>
                            <button @click="router.get(route('admin.toggle-view', 'student'))">
                                View as Student
                            </button>
                            <button @click="router.get(route('admin.toggle-view', 'pal'))">
                                View as Pal
                            </button>
                        </template>
                    </div>
                </div>
                <div v-if="UserStore.isUser" class="nav-user-menu">
                    <div class="nav-user-menu-head">{{ $t('nav.sidebar.my-account') }}</div>
                    <div class="nav-user-menu-items">
                        <Link :href="route('subscription.index')">{{ $t('nav.sidebar.manage-subscription') }}</Link>
                        <Link :href="route('password.edit')">{{ $t('nav.sidebar.change-password') }}</Link>
                        <a v-if="!UserStore.user.has_discord" :href="route('auth.discord')">
                            {{ $t('nav.sidebar.link-discord') }}
                        </a>
                        <button v-else @click="router.post(route('auth.discord.revoke'))">
                            {{ $t('nav.sidebar.unlink-discord') }}
                        </button>
                    </div>
                </div>
                <div v-if="UserStore.isUser" class="nav-user-menu">
                    <div class="nav-user-menu-head">{{ $t('nav.sidebar.get-help') }}</div>
                    <div class="nav-user-menu-items">
                        <button @click="NavigationStore.showSendFeedback = true">
                            {{ $t('nav.sidebar.send-feedback') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-overlay"></div>
    </div>

    <ModalWrapper v-model="NavigationStore.showSendFeedback">
        <SendFeedback @close="NavigationStore.showSendFeedback = false"/>
    </ModalWrapper>
    <ModalWrapper v-model="showSendMail">
        <SendMail @close="showSendMail = false"/>
    </ModalWrapper>
    <ModalWrapper v-model="NavigationStore.showSignIn">
        <SignIn @close="NavigationStore.showSignIn = false"
                @signUp="NavigationStore.showSignIn = false; NavigationStore.showSignUp = true"/>
    </ModalWrapper>
    <ModalWrapper v-model="NavigationStore.showSignUp">
        <SignUp @close="NavigationStore.showSignUp = false"
                @signIn="NavigationStore.showSignUp = false; NavigationStore.showSignIn = true"/>
    </ModalWrapper>
</template>

<style scoped lang="scss">
.nav-sidebar-container {
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    height: 100%;
    display: grid;
    grid-template: 1fr / 1fr;
    z-index: 999;

    .nav-sidebar {
        grid-area: 1 / 1;
        display: flex;
        flex-direction: column;
        max-height: 100vh;
        transition: transform 0.3s ease-in-out;
        z-index: 999;

        // Mobile
        width: 100%;
    }

    .nav-overlay {
        grid-area: 1 / 1;
        z-index: 998;
        width: 100%;

        // Mobile
        display: none;
    }

    &.show {
        .nav-sidebar {
            // Mobile
            transform: translateX(-100vw);
        }

        .nav-overlay {
            opacity: 1;
            transform: translateX(-100vw);
            transition: opacity 0.3s ease-in-out;
        }
    }

    @media (width >= 960px) {
        .nav-sidebar {
            width: 36rem;
            border-inline-start: 0.2rem solid var(--color-dark-primary);
        }

        .nav-overlay {
            display: block;
            transform: translateX(-200vw);
        }

        &.show {
            .nav-sidebar {
                transform: translateX(-36rem);
            }
        }
    }
}

.nav-sidebar-head {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    background: var(--color-medium-secondary);
    color: white;
    font-family: var(--mono-font);
    font-size: 1.6rem;
    font-weight: 700;
    height: 3em;

    div {
        text-transform: uppercase;
        flex-grow: 1;
        font-size: 1.6rem;
        padding-inline: 1.6rem;
        user-select: none;

        a {
            font-size: 1.2rem;
            color: var(--color-pastel-medium);
        }
    }

    & > button {
        color: white;
        font-size: 1.5em;
        width: 2em;
        height: 100%;

        &:hover {
            color: var(--color-dark-primary);
            background: var(--color-accent-medium);
        }
    }

    @media (width >= 960px) {
        font-size: 1.2rem;

        div {
            padding-inline: 1.2rem;
        }
    }
}

.nav-sidebar-body {
    display: grid;
    grid-auto-rows: max-content;
    gap: 3.2rem;
    background: white;
    box-shadow: 0 0.3rem 0 rgb(0 0 0 / 0.25);
    padding: 1.6rem;
    flex-grow: 1;
    overflow: scroll;
    position: relative;

    .nav-carousel-wrapper {
        display: grid;
        gap: 1.6rem;
        border-block-end: 0.2rem solid var(--color-accent-medium);
    }

    .nav-carousel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;

        div {
            flex-grow: 1;
            text-align: right;
        }

        & > * {
            color: var(--color-dark-primary);
            font-family: var(--mono-font);
            font-size: 1.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    }

    li.carousel__slide {
        display: grid;
        align-content: start;
        grid-auto-columns: 1fr;
        font-family: var(--head-font);
        padding-inline: 0 !important;
    }

    li.carousel__slide.nav-carousel-slide {
        grid-template-areas: 1 / 1;
        display: grid;

        .nav-carousel-section {
            grid-area: 1 / 1;
            background: white;
            height: 100%;
        }
    }

    .nav-carousel-page-item {
        padding: 1.2rem 2.0rem;
        user-select: none;
        color: var(--color-dark-primary);
        display: grid;
        gap: 0.4rem;
        padding-block-start: 2.4rem;
        cursor: pointer;

        &:hover {
            background: var(--color-accent-light);
        }

        *:first-child {
            font-weight: 700;
            font-size: 2.4rem;
            color: var(--color-dark-primary);

        }

        *:last-child {
            font-size: 1.2rem;
            font-family: var(--mono-font);
            line-height: 1.25;
        }

        &[disabled='true'], &.disabled {
            opacity: 50%;
            cursor: not-allowed;
            filter: grayscale(100%);
        }

        &.active {
            background: var(--color-dark-primary);
            color: white;

            *:first-child {
                font-size: 2.4rem;
                color: var(--color-accent-medium);
            }
        }
    }
}

.nav-overlay {
    background: var(--color-transparent);
    display: grid;

    gap: 6.4rem;
    opacity: 0;
    transition: opacity 0.3s ease-in-out, transform 0s ease-in-out 0.3s;
    user-select: none;

    // Mobile
    padding: 3.2rem;
}

.nav-portal-wrapper {
    display: flex;
    justify-content: space-around;
    padding: 1.6rem 0.8rem;

    .nav-portal {
        display: grid;
        justify-items: center;
        align-items: center;
        grid-template-areas: 1fr / 1fr;
        cursor: pointer;

        & > * {
            grid-area: 1 / 1;
        }

        div {
            text-transform: uppercase;
            font-family: var(--display-font);
            font-size: 3.6rem;
            line-height: 0.8;
            padding-inline: 0.8rem;
            padding-block-end: 0.4rem;
            color: var(--color-polar-light);
            background: var(--color-medium-secondary);
        }

        img {
            width: 8.0rem;
        }

        &:hover div {
            color: var(--color-medium-secondary);
            background: var(--color-accent-light);
        }
    }
}

.nav-user-menu {
    display: grid;
    gap: 0.8rem;
    padding-block-end: 0.8rem;
    border-block-end: 0.2rem solid var(--color-accent-medium);

    .nav-user-menu-head {
        font-family: var(--mono-font);
        font-size: 2.0rem;
        font-weight: 700;
        padding: 0.8rem 1.6rem;
        color: var(--color-polar-light);
        background: var(--color-medium-secondary);
    }

    .nav-user-menu-items {
        display: grid;

        & > * {
            font-family: var(--mono-font);
            font-size: 1.6rem;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            color: var(--color-dark-primary);
            text-align: start;

            &:hover {
                background: var(--color-accent-light);
            }
        }
    }
}

@media (width >= 960px) {
    .nav-sidebar {
        box-shadow: -0.3rem 0 0 rgb(0 0 0 / 0.25);

        .featured-title {
            justify-self: auto;
        }
    }

    .nav-overlay {
        padding: 6.4rem;
    }
}
</style>
