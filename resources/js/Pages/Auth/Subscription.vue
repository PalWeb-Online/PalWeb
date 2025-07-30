<script setup>
import Layout from "../../Shared/Layout.vue";
import AppTip from "../../components/AppTip.vue";
import {useNavigationStore} from "../../stores/NavigationStore.js";
import {useUserStore} from "../../stores/UserStore.js";
import {usePage} from "@inertiajs/vue3";

const NavigationStore = useNavigationStore();
const UserStore = useUserStore();

defineOptions({
    layout: Layout,
});

const page = usePage();

const navigate = () => {
    if (!UserStore.isUser) {
        NavigationStore.showSignUp = true;

    } else {
        window.location.href = '/billing';
    }
}
</script>
<template>
    <Head title="Subscribe"/>
    <div id="app-head">
        <h1>Subscription</h1>
    </div>
    <div id="app-body">
        <AppTip v-if="page.props.flash.denied">
            <p>Sorry, but you currently don't have access to this content.
                {{ !UserStore.isUser
                    ? 'Please sign in or create an account first.'
                    : 'Please review & update your Subscription & try again.'
                }}
            </p>
        </AppTip>
        <div class="subscription-tiers">
            <div v-if="!UserStore.isUser" class="tier-item window-container">
                <div class="window-section-head">
                    <h1>Guest</h1>
                </div>
                <div class="tier-body">
                    <div>Access to <b>Wiki</b></div>
                    <div>Access to <b>Library: Dictionary</b></div>
                    <div>Access to <b>Library: Phrasebook</b></div>
                </div>
                <div class="window-footer">
                    <div style="background: var(--color-accent-dark); color: white;">you are here</div>
                </div>
            </div>
            <div class="tier-item window-container">
                <div class="window-section-head">
                    <h1>Pal</h1>
                </div>
                <div class="tier-body">
                    <div class="highlighted">Access to <b>Wiki</b></div>
                    <div class="highlighted">Access to <b>Library: Dictionary</b></div>
                    <div class="highlighted">Access to <b>Library: Phrasebook</b></div>
                    <div>Access to <b>Library: Decks</b></div>
                    <div>Access to <b>Library: Audios</b></div>
                    <div><b>Pin</b> Terms, Sentences & Decks</div>
                    <div>Access to <b>Workbench: Deck Master</b></div>
                    <div>Access to <b>Workbench: Record Wizard</b></div>
                </div>
                <div class="window-footer">
                    <div v-if="UserStore.highestRole === 'pal'"
                        style="background: var(--color-accent-dark); color: white;">you are here</div>
                    <button v-else @click="navigate">free</button>
                </div>
            </div>
            <div class="tier-item window-container">
                <div class="window-section-head">
                    <h1>Student</h1>
                </div>
                <div class="tier-body">
                    <div class="highlighted">Access to <b>Wiki</b></div>
                    <div class="highlighted">Access to <b>Library: Dictionary</b></div>
                    <div class="highlighted">Access to <b>Library: Phrasebook</b></div>
                    <div class="highlighted">Access to <b>Library: Decks</b></div>
                    <div class="highlighted">Access to <b>Library: Audios</b></div>
                    <div class="highlighted"><b>Pin</b> Terms, Sentences & Decks</div>
                    <div class="highlighted">Access to <b>Workbench: Deck Master</b></div>
                    <div class="highlighted">Access to <b>Workbench: Record Wizard</b></div>
                    <div>Access to <b>Academy: Lessons</b></div>
                    <div>Access to <b>Academy: Dialogs</b></div>
                    <div>Access to <b>Academy: Quizzer</b></div>
                    <div>Access to <b>Academy: myProgress</b></div>
                    <div><b>Support</b> the Project</div>
                </div>
                <div class="window-footer">
                    <div v-if="UserStore.highestRole === 'student'"
                         style="background: var(--color-accent-dark); color: white;">you are here</div>
                    <button v-else @click="navigate">$12/m $80/y</button>
                </div>
            </div>
        </div>
    </div>
</template>
