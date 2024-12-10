<script setup>
import {onBeforeUnmount, onMounted} from 'vue';
import {useStateStore} from './store/StateStore';
import {useRecordStore} from "./store/RecordStore.js";
import {useNavigationStore} from './store/NavigationStore';
import Speaker from './pages/Speaker.vue';
import Queue from './pages/Queue.vue';
import Studio from './pages/Studio.vue';
import Review from './pages/Review.vue';
import WizardButton from './ui/WizardButton.vue';
import WizardDialog from "./ui/WizardDialog.vue";
import Record from "../../utils/Record.js";

const StateStore = useStateStore();
const RecordStore = useRecordStore();
const {
    prev,
    next,
    retry,
} = useNavigationStore();

const exit = async () => {
    if (confirm('Are you sure you want to leave the wizard? All your stashed recordings will be lost.')) {
        await RecordStore.clearStash();
        window.location.href = '/dashboard/workbench';
    }
};

const preventWindowClose = (event) => {
    if (StateStore.hasPendingRequests) {
        event.preventDefault();
        event.returnValue = '';
    } else {
        RecordStore.clearStash();
    }
};

onMounted(() => {
    window.addEventListener('beforeunload', preventWindowClose);
});

onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', preventWindowClose);
});

</script>

<template>
    <div id="rw-head">
        <button @click="exit">Exit to Workbench</button>
        <h1>Record Wizard</h1>

        <div id="rw-nav">
            <img :class="StateStore.prevDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="prev"/>
            <div class="rw-nav-steps">
                <div :class="{ active: StateStore.data.step === 'speaker' }">Speaker</div>
                <div :class="{ active: StateStore.data.step === 'queue' }">Queue</div>
                <div :class="{ active: StateStore.data.step === 'studio' }">Studio</div>
                <div :class="{ active: StateStore.data.step === 'review' }">Review</div>
            </div>
            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="next"/>

            <WizardButton
                id="mwe-rw-retry"
                icon="reload"
                label="Retry"
                @click="retry"
                v-show="StateStore.showRetry"
            />

            <!--                TODO: Return from Review to Queue. -->
            <!--                <WizardButton-->
            <!--                    id="mwe-rw-restart"-->
            <!--                    icon="next"-->
            <!--                    label="Restart"-->
            <!--                    flags="progressive primary"-->
            <!--                    @click="next"-->
            <!--                    v-show="StateStore.data.isUploading === true && !StateStore.hasPendingRequests"-->
            <!--                />-->
        </div>
    </div>

    <div id="rw-main">

        <!--            <div v-if="!StateStore.data.isContentVisible" class="rw-spinner">-->
        <!--                <img class="loading" src="/img/wait.svg" alt="Loading"/>-->
        <!--            </div>-->

        <div class="rw-content-wrapper">
            <div class="rw-content">
                <div id="rw-speaker" v-if="StateStore.data.step === 'speaker'">
                    <Speaker/>
                </div>
                <div id="rw-queue" v-if="StateStore.data.step === 'queue'">
                    <Queue/>
                </div>
                <div id="rw-studio" v-if="StateStore.data.step === 'studio'">
                    <Studio/>
                </div>
                <div id="rw-review" v-if="StateStore.data.step === 'review'">
                    <Review/>
                </div>
            </div>

            <WizardDialog size="large">
                <template #trigger>
                    <img alt="Info" src="/img/idea.svg"/>
                </template>

                <template #content v-if="StateStore.data.step === 'speaker'">
                    <div>What is my Speaker profile?</div>
                    <p>Your Speaker profile contains linguistic data about you that will be connected to every
                        Recording you
                        create, so that others can know the dialect & other sociolinguistic information behind what
                        they're
                        hearing. Your Speaker profile is distinct from your User profile; it does not include your
                        name,
                        etc. By default, however, your Recordings will link to your User profile. If you would like
                        for your
                        Speaker profile to remain anonymous, simply return to the Dashboard & set your User profile
                        to
                        Private. You can change this at any time.</p>
                    <div>What is my Location?</div>
                    <p>Your Location is the place where you learned Arabic, or the place that the people with whom
                        you
                        learned Arabic (e.g. your relatives) are from. It is up to you to decide the most
                        appropriate choice
                        to select. If you have lived most of your life in a given town, but your family & you have
                        an accent
                        characteristic of another town, then the town of your ancestry may be a more appropriate
                        selection. Select the Location that you feel best represents your manner of speaking.</p>
                    <p><b>Note:</b> These Locations were imported from Wikidata by means of a script. There was no
                        way to
                        reliably distinguish Israeli towns in the 1948 Territories from Palestinian ones, so all
                        towns
                        currently located in the 1948 Territories — including Israeli towns — are listed. Israeli
                        settlements in the West Bank & the Golan Heights have been discarded, however.
                    </p>
                    <div>What is my Fluency level?</div>
                    <p>Your Fluency level reflects how natural your pronunciation is, according to your own
                        assessment. Only
                        choose <b>Native</b> if you were raised speaking the language & have used it throughout your
                        life,
                        especially in a region where the language is spoken natively. Choose <b>Fluent</b> if your
                        pronunciation of the language is naturalistic for any other reason. Most learners should not
                        select
                        anything higher than <b>Advanced</b>. Heritage speakers may fall anywhere on this spectrum.
                        Err on
                        the side of underestimation.</p>
                </template>

                <template #content v-if="StateStore.data.step === 'queue'">
                    <div>What is the Queue?</div>
                    <p>The Queue is the list of Pronunciation items you will be recording.</p>
                    <p><b>You may only queue up a maximum of 100 items.</b> (After uploading your recordings, you may
                        return to
                        the Queue page to refresh the list with another 100 items.)</p>
                    <p><b>You may only record any given item once — one Audio per Pronunciation per Speaker.</b> If an
                        item that you expected to see in the Queue seems to be missing, you have probably already
                        recorded it.</p>

                    <div>What does Auto Queue do?</div>
                    <p>
                        Click <b>Fetch</b> to automatically queue up the next 100 Pronunciations in your Dialect
                        available
                        for you to record. If you choose to discard any item, you can click <b>Fetch</b> again to fill
                        the
                        Queue back up to 100 with the next available Pronunciations.</p>
                    <p>With <b>Auto Queue</b>, Pronunciations are sorted by Audio count: Pronunciations with no Audios
                        are listed first, followed by those with at least one, then two & so on.
                    </p>
                    <div>What does Queue Deck do?</div>
                    <p>Click <b>Select</b> to pull up your Pinned Decks & choose any one of them to queue up any of its
                        terms that you haven't recorded yet. You can only queue up one Deck at a time; click
                        <b>Select</b> again to switch your queued-up Deck. While you can't search for individual terms
                        to add manually to the Queue, you can create a Deck of terms you'd like to record, keep it
                        pinned & select it here.</p>
                    <p>With <b>Queue Deck</b>, Pronunciations are not sorted by Audio count; they are listed in the
                        order in which they appear in the Deck.</p>
                </template>
            </WizardDialog>
        </div>
    </div>
</template>
