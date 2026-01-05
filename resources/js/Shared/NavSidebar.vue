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
                        <Link :href="route('wiki.show', 'about')" class="nav-portal">
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
