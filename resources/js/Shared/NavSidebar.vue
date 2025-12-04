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
                    {{ UserStore.user ? UserStore.highestRole : 'Guest' }}
                    <Link v-if="UserStore.user" class="auth-role" :href="route('subscription.index')">Manage</Link>
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
                        <div>{{ NavigationStore.data.section }}</div>
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
                                <div>academy</div>
                                <div>for Students only</div>
                            </div>
                            <div @click="toSection('library', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'library' }">
                                <div>library</div>
                                <div>the Web of Palestinian Arabic</div>
                            </div>
                            <div @click="toSection('workbench', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'workbench' }">
                                <div>workbench</div>
                                <div>database-powered tools</div>
                            </div>
                            <div v-if="['admin'].includes(UserStore.highestRole)"
                                 @click="toSection('office', 1)" class="nav-carousel-page-item"
                                 :class="{ 'active': NavigationStore.data.section === 'office' }">
                                <div>office</div>
                                <div>for Teachers only</div>
                            </div>
                        </Slide>
                        <Slide class="nav-carousel-slide" key="1">
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.academy }">
                                <div @click="navigateOrPrompt('coming-soon')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                        'active': $page.component === 'Workbench/Index',
                                        'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>lessons</div>
                                    <div>learn Palestinian Arabic</div>
                                </div>
                                <div @click="navigateOrPrompt('dialogs.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Academy/Dialogs/Index', 'Academy/Dialogs/Show'].includes($page.component),
                                         'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>dialogs</div>
                                    <div>natural language input</div>
                                </div>
                                <div @click="navigateOrPrompt('scores.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Academy/Scores/Index', 'Academy/Scores/History'].includes($page.component),
                                         'disabled': !['student', 'admin'].includes(UserStore.highestRole)
                                     }"
                                >
                                    <div>scores</div>
                                    <div>view your Score history</div>
                                </div>
                            </div>
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.library }">
                                <Link :href="route('terms.index')"
                                      class="nav-carousel-page-item"
                                      :class="{ 'active': ['Library/Terms/Index', 'Library/Terms/Show'].includes($page.component) }"
                                >
                                    <div>dictionary</div>
                                    <div>Term::all()</div>
                                </Link>
                                <Link :href="route('sentences.index')"
                                      class="nav-carousel-page-item"
                                      :class="{ 'active': ['Library/Sentences/Index', 'Library/Sentences/Show'].includes($page.component) }"
                                >
                                    <div>corpus</div>
                                    <div>Sentence::with('terms')->all()</div>
                                </Link>
                                <div @click="navigateOrPrompt('decks.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Library/Decks/Index', 'Library/Decks/Show'].includes($page.component),
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>decks</div>
                                    <div>Deck::where('private', false)->all()</div>
                                </div>
                                <div @click="navigateOrPrompt('audios.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': ['Library/Audios/Index', 'Library/Audios/Speaker'].includes($page.component),
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>audios</div>
                                    <div>Audio::with('speaker')->all()</div>
                                </div>
                            </div>
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.workbench }">
                                <div @click="navigateOrPrompt('deck-master.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': $page.component === 'Workbench/DeckMaster/DeckMaster',
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>deckMaster</div>
                                    <div>build & study Decks</div>
                                </div>
                                <div @click="navigateOrPrompt('record-wizard.index')"
                                     class="nav-carousel-page-item"
                                     :class="{
                                         'active': $page.component === 'Workbench/RecordWizard/RecordWizard',
                                         'disabled': !UserStore.isUser
                                     }"
                                >
                                    <div>recordWizard</div>
                                    <div>immortalize Palestinian Arabic</div>
                                </div>
                            </div>
                            <div class="nav-carousel-section" :style="{ zIndex: zIndices.office }">
                                <div
                                    @click="navigateOrPrompt('word-logger.index')"
                                    class="nav-carousel-page-item"
                                    :class="{ 'active': $page.component === 'Office/WordLogger/Index' }"
                                >
                                    <div>wordLogger</div>
                                    <div>manage Dictionary entries</div>
                                </div>
                                <div
                                    @click="navigateOrPrompt('speech-maker.index')"
                                    class="nav-carousel-page-item"
                                    :class="{ 'active': $page.component === 'Office/SpeechMaker/SpeechMaker' }"
                                >
                                    <div>speechMaker</div>
                                    <div>create Sentences & Dialogs</div>
                                </div>
                                <div
                                    @click="navigateOrPrompt('lesson-planner.index')"
                                    class="nav-carousel-page-item"
                                    :class="{ 'active': $page.component === 'Office/LessonPlanner/Course' }"
                                >
                                    <div>lessonPlanner</div>
                                    <div>create Lessons & Activities</div>
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

                <div v-if="UserStore.isAdmin" class="nav-user-menu">
                    <div class="nav-user-menu-head">myAdmin</div>
                    <div class="nav-user-menu-items">
                        <button @click="showSendMail = true">Send Mail</button>
                    </div>
                </div>
                <div v-if="UserStore.isUser" class="nav-user-menu">
                    <div class="nav-user-menu-head">myAccount</div>
                    <div class="nav-user-menu-items">
                        <Link :href="route('subscription.index')">Manage Subscription</Link>
                        <Link :href="route('password.edit')">Change Password</Link>
                        <button v-if="UserStore.user.has_discord" @click="router.post(route('auth.discord.revoke'))">
                            Unlink
                            from Discord
                        </button>
                        <a v-else :href="route('auth.discord')">Link to Discord</a>
                    </div>
                </div>
                <div v-if="UserStore.isUser" class="nav-user-menu">
                    <div class="nav-user-menu-head">getHelp</div>
                    <div class="nav-user-menu-items">
                        <button @click="NavigationStore.showSendFeedback = true">Send Feedback</button>
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
