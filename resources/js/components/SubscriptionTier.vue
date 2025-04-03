<script setup>
import {useUserStore} from "../stores/UserStore.js";
import {useNavigationStore} from "../stores/NavigationStore.js";

const props = defineProps({
    tier: String,
})

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

const navigate = () => {
    if (UserStore.highestRole === 'guest') {
        NavigationStore.showSignUp = true;

    } else {
        window.location.href = '/billing';
    }
}
</script>

<template>
    <div class="tier-item-wrapper">
        <div class="tier-name">{{ tier }}</div>
        <div @click="navigate" class="tier-item" style="cursor: pointer">
            <div class="tier-body">
                <div>Access to <b>Wiki</b></div>
                <div>Access to <b>Library: Dictionary</b></div>
                <div>Access to <b>Library: Sentences</b></div>
                <div :class="['pal', 'student'].includes(tier) ? '' : 'disabled'">
                    Access to <b>Library: Decks</b>
                </div>
                <div :class="['pal', 'student'].includes(tier) ? '' : 'disabled'">
                    Access to <b>Library: Audios</b>
                </div>
                <div :class="['pal', 'student'].includes(tier) ? '' : 'disabled'">
                    <b>Pin</b> Terms, Sentences & Decks
                </div>
                <div :class="['pal', 'student'].includes(tier) ? '' : 'disabled'">
                    Use the <b>Deck Master</b>
                </div>
                <div :class="['pal', 'student'].includes(tier) ? '' : 'disabled'">
                    Use the <b>Record Wizard</b>
                </div>
                <div :class="['pal', 'student'].includes(tier) ? '' : 'disabled'">
                    <b>Request</b> Terms
                </div>
                <div :class="tier === 'student' ? '' : 'disabled'">
                    Access to <b>Explore</b> Portal
                </div>
                <div :class="tier === 'student' ? '' : 'disabled'">
                    Access to <b>Academy</b></div>
                <div :class="tier === 'student' ? '' : 'disabled'">
                    Support the Project
                </div>
            </div>
            <div class="tiers-pricing">{{ tier === 'student' ? '$12/m $80/y' : 'FREE' }}</div>
        </div>

        <div v-if="UserStore.highestRole === tier" class="current-tier">you are here</div>
    </div>
</template>
