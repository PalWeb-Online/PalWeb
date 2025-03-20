<script setup>
import {useUserStore} from "../stores/UserStore.js";
import {computed} from "vue";

const props = defineProps({
    tier: String,
})

const UserStore = useUserStore();

const unlocked = computed(() => {
    if (props.tier === 'pal') return UserStore.isUser;
    if (props.tier === 'student') return UserStore.isStudent || UserStore.isAdmin;
})
</script>

<template>
    <div class="tier-item-wrapper">
        <div class="tier-name">{{ tier }}</div>
        <a href="/billing" class="tier-item">
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
        </a>

<!--        todo: compute based on highest tier -->
        <div v-if="tier === 'student'" class="current-tier">you are here</div>
    </div>

</template>
