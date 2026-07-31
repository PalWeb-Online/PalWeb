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
        <h1>{{ $t('subscription.title') }}</h1>
    </div>
    <div id="app-body">
        <AppTip v-if="page.props.flash.denied">
            <p>{{ $t('subscription.messages.access-denied') }}
                {{ !UserStore.isUser
                    ? $t('subscription.messages.sign-in-prompt')
                    : $t('subscription.messages.update-subscription-prompt')
                }}
            </p>
        </AppTip>
        <Link href="/billing" v-if="UserStore.isUser" class="portal-button">{{ $t('subscription.manage') }}</Link>
        <div class="subscription-tiers">
            <div v-if="!UserStore.isUser" class="tier-item window-container">
                <div class="window-section-head">
                    <h1>{{ $t('user.roles.guest') }}</h1>
                </div>
                <div class="tier-body">
                    <div>Read the <b>Wiki</b></div>
                    <div><b>Library: Dictionary & Corpus</b></div>
                </div>
                <div class="window-footer">
                    <div style="background: var(--color-accent-dark); color: white;">{{ $t('subscription.current-plan') }}</div>
                </div>
            </div>
            <div class="tier-item window-container">
                <div class="window-section-head">
                    <h1>{{ $t('user.roles.pal') }}</h1>
                </div>
                <div class="tier-body">
                    <div>Read the <b>Wiki</b></div>
                    <div><b>Library: Dictionary & Corpus</b></div>
                    <div><b>Library: Decks & Audios</b></div>
                    <div><b>Pin</b> Terms, Sentences & Decks</div>
                    <div><b>Sound Booth</b></div>
                    <div><b>Deck Master</b>: Build Decks</div>
                    <div><b>Deck Master</b>: Study Decks (<b>Practice</b>)</div>
                </div>
                <div class="window-footer">
                    <div v-if="UserStore.highestRole === 'pal'"
                        style="background: var(--color-accent-dark); color: white;">{{ $t('subscription.current-plan') }}</div>
                    <button v-else @click="navigate">{{ $t('subscription.free') }}</button>
                </div>
            </div>
            <div class="tier-item window-container">
                <div class="window-section-head">
                    <h1>{{ $t('user.roles.student') }}</h1>
                </div>
                <div class="tier-body">
                    <div>Read the <b>Wiki</b></div>
                    <div><b>Library: Dictionary & Corpus</b></div>
                    <div><b>Library: Decks & Audios</b></div>
                    <div><b>Pin</b> Terms, Sentences & Decks</div>
                    <div><b>Sound Booth</b></div>
                    <div><b>Deck Master</b>: Build Decks</div>
                    <div><b>Deck Master</b>: Study Decks (<b>Practice</b> & <b>Quiz</b>)</div>
                    <div><b>Card Dealer</b> (SRS Review)</div>
                    <div>the <b>Academy</b> (<b>Lessons</b>, <b>Dialogs</b>, etc.)</div>
                    <div>Save your Quiz & Activity <b>Scores</b></div>
                    <div><b>Support</b> the Project</div>
                </div>
                <div class="window-footer">
                    <div v-if="UserStore.highestRole === 'student'"
                         style="background: var(--color-accent-dark); color: white;">{{ $t('subscription.current-plan') }}</div>
                    <button v-else @click="navigate">{{ $t('subscription.price', { monthlyPrice: '$12', yearlyPrice: '$80' }) }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.portal-button {
    margin-block: 3.2rem;

    @media (width >= 960px) {
        margin: 0
    }
}

.subscription-tiers {
    width: 100%;
    max-width: 128rem;
    margin: 0 6.4rem 3.2rem;
    padding-inline: 3.2rem;
    gap: 3.2rem;
    display: flex;
    flex-flow: row wrap;
    align-items: flex-start;
    justify-content: center;
}

.tier-item {
    border-radius: 0.8rem;
    border: 0.2rem solid var(--color-dark-primary);
    box-shadow: -0.3rem 0.3rem 0 rgb(0 0 0 / 0.25);
    max-width: 36rem;

    .tier-body {
        display: grid;
        text-align: left;
        color: var(--color-dark-primary);

        & > * {
            padding: 1.2rem;
        }

        & > *:not(:first-child) {
            border-block-start: 0.1rem solid var(--color-pastel-dark);
        }

        & > *:last-child {
            border-block-end: none;
        }
    }
}
</style>
