<script setup>
import {useUserStore} from "../stores/UserStore.js";
import {useNavigationStore} from "../stores/NavStore.js";
import {Link} from '@inertiajs/inertia-vue3'
import {route} from 'ziggy-js';
import {ref} from "vue";
import {Carousel, Slide} from "vue3-carousel";
import ThemePicker from "../components/ThemePicker.vue";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

const carouselRef = ref(null);

const isOpen = ref(false);
const toggleOpen = () => {
    isOpen.value = !isOpen.value;
}

const toSection = async (section, key) => {
    NavigationStore.data.section = section;
    carouselRef.value?.slideTo(key);
}
</script>
<template>
    <div id="workbench" class="nav-user" :class="{ 'show': isOpen }">
        <div class="nav-user-dashboard">
            <div class="featured-title l">Menu</div>

            <div @click="toggleOpen" class="nav-user-dashboard-profile">
                <div class="user-avatar">
                    <div class="material-symbols-rounded">close</div>
                    <img alt="Profile Picture"
                         :src="`/img/avatars/${UserStore.user.avatar}`"/>
                </div>
                <div class="user-nametag">
                    <div>{{ UserStore.user.name }}</div>
                    <div>({{ UserStore.user.username }})</div>
                </div>
            </div>

            <div class="nav-user-dashboard-options-wrapper">
                <div class="nav-user-menu-head">
                    <button v-if="NavigationStore.data.section !== 'home'" @click="toSection('home', 0)"><-</button>
                    <div>{{ NavigationStore.data.section }}</div>
                </div>
                <Carousel
                    :items-to-show="1"
                    ref="carouselRef"
                >
                    <Slide key="0">
                        <div class="nav-user-dashboard-options">
                            <div @click="toSection('academy', 1)" class="nav-user-dashboard-option"
                                 :class="{ 'active': NavigationStore.data.section === 'academy' }">
                                <div>academy</div>
                                <div>for Students only</div>
                            </div>
                            <div @click="toSection('library', 2)" class="nav-user-dashboard-option"
                                 :class="{ 'active': NavigationStore.data.section === 'library' }">
                                <div>library</div>
                                <div>the Web of Palestinian Arabic</div>
                            </div>
                            <div @click="toSection('community', 3)" class="nav-user-dashboard-option"
                                 :class="{ 'active': NavigationStore.data.section === 'community' }">
                                <div>community</div>
                                <div>we make it</div>
                            </div>
                            <div @click="toSection('workbench', 4)" class="nav-user-dashboard-option"
                                 :class="{ 'active': NavigationStore.data.section === 'workbench' }">
                                <div>workbench</div>
                                <div>database-powered learning tools</div>
                            </div>
                        </div>
                    </Slide>
                    <Slide key="1">
                        <div class="nav-user-dashboard-options">
                            <template v-if="NavigationStore.data.section === 'academy'">
                                <Link :href="route('workbench.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/Index' }"
                                >
                                    <div>lessons</div>
                                    <div>learn Palestinian Arabic</div>
                                </Link>
                                <Link :href="route('dialogs.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': ['Academy/Dialogs/Index', 'Academy/Dialogs/Show'].includes($page.component) }"
                                >
                                    <div>dialogs</div>
                                    <div>natural language input</div>
                                </Link>
                                <Link :href="route('workbench.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/Index' }"
                                >
                                    <div>quizzer</div>
                                    <div>quiz Decks, Skills & Dialogs</div>
                                </Link>
                                <Link :href="route('workbench.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/Index' }"
                                >
                                    <div>myProgress</div>
                                    <div>check progress & Score history</div>
                                </Link>
                            </template>
                            <template v-if="NavigationStore.data.section === 'library'">
                                <Link :href="route('terms.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': ['Library/Terms/Index', 'Library/Terms/Show'].includes($page.component) }"
                                >
                                    <div>dictionary</div>
                                    <div>Term::all()</div>
                                </Link>
                                <Link :href="route('sentences.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': ['Library/Sentences/Index', 'Library/Sentences/Show'].includes($page.component) }"
                                >
                                    <div>sentences</div>
                                    <div>Sentence::with('terms')->all()</div>
                                </Link>
                                <Link :href="route('decks.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': ['Library/Decks/Index', 'Library/Decks/Show'].includes($page.component) }"
                                >
                                    <div>decks</div>
                                    <div>Deck::where('private', false)->all()</div>
                                </Link>
                                <Link :href="route('audios.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': ['Library/Audios/Index', 'Library/Audios/Speaker'].includes($page.component) }"
                                >
                                    <div>audios</div>
                                    <div>Audio::with('speaker')->all()</div>
                                </Link>
                            </template>
                            <template v-if="NavigationStore.data.section === 'community'">
                                <Link :href="route('workbench.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/Index' }"
                                >
                                    <div>Hub</div>
                                    <div>see where it's at</div>
                                </Link>
                                <Link :href="route('workbench.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/Index' }"
                                >
                                    <div>myProfile</div>
                                    <div>view & adjust your Profile</div>
                                </Link>
                            </template>
                            <template v-if="NavigationStore.data.section === 'workbench'">
                                <Link :href="route('workbench.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/PinBoard' }"
                                >
                                    <div>pinBoard</div>
                                    <div>see your pinned Terms & Sentences</div>
                                </Link>
                                <Link :href="route('deck-master.index')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/DeckMaster' }"
                                >
                                    <div>deckMaster</div>
                                    <div>study pinned Decks or build new</div>
                                </Link>
                                <Link :href="route('audios.record')"
                                      class="nav-user-dashboard-option"
                                      :class="{ 'active': $page.component === 'Workbench/RecordWizard' }"
                                >
                                    <div>recordWizard</div>
                                    <div>immortalize Palestinian Arabic</div>
                                </Link>
                            </template>
                        </div>
                    </Slide>
                </Carousel>
            </div>
            <ThemePicker />
            <div class="nav-admin-menu">
                <div class="nav-admin-menu-head">Admin</div>
                <div class="nav-admin-menu-items">
                    <Link :href="route('terms.create')">New Term</Link>
                    <div>New Sentence</div>
                    <Link :href="route('dialogs.create')">New Dialog</Link>
                    <div>New Mail</div>
                    <div>Terms to-Do</div>
                    <div>Sentences to-Do</div>
                </div>
            </div>

        </div>
    </div>
</template>
