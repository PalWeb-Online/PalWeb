<script setup>
import {onBeforeUnmount, onMounted} from 'vue';
import {useStateStore} from './store/StateStore';
import {useNavigationStore} from './store/NavigationStore';
import Testing from './pages/Testing.vue';
import Speaker from './pages/Speaker.vue';
import Queue from './pages/Queue.vue';
import Studio from './pages/Studio.vue';
import Finish from './pages/Finish.vue';
import WizardButton from './ui/WizardButton.vue';

const StateStore = useStateStore();
const {
    prev,
    next,
    retry,
} = useNavigationStore();

const cancel = () => {
    if (confirm('Are you sure you want to leave the wizard? All your stashed recordings will be lost.')) {
        clearStash();
        window.location.href = '/dashboard/workbench';
    }
};

const preventWindowClose = (event) => {
    if (StateStore.hasPendingRequests) {
        event.preventDefault();
        event.returnValue = '';
    } else {
        clearStash();
    }
};

const clearStash = () => {
    fetch('/api/record/clear-stash', { method: 'DELETE' })
        .then(response => {
            if (response.ok) {
                console.log('Stash directory cleaned up successfully.');
            } else {
                console.error('Failed to clean up stash directory.');
            }
        })
        .catch(error => console.error('Error during stash cleanup:', error));
};

onMounted(() => {
    setTimeout(() => {
        StateStore.data.isContentVisible = true;
    }, 1000);

    window.addEventListener('beforeunload', preventWindowClose);
});

onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', preventWindowClose);
});

</script>

<template>
    <h1>Record Wizard</h1>
    <div id="mwe-rw">
        <div id="mwe-rw-steps">
            <div :class="{ active: StateStore.data.step === 'testing' }">Testing</div>
            <div :class="{ active: StateStore.data.step === 'speaker' }">Speaker</div>
            <div :class="{ active: StateStore.data.step === 'queue' }">Queue</div>
            <div :class="{ active: StateStore.data.step === 'studio' }">Studio</div>
            <div :class="{ active: StateStore.data.step === 'finish' }">Finish</div>
        </div>

        <div id="mwe-rw-main">
            <div id="wizard-navigation">
                <WizardButton
                    id="mwe-rw-cancel"
                    label="Cancel"
                    :framed="false"
                    @click="cancel"
                />

                <WizardButton
                    id="mwe-rw-prev"
                    icon="previous"
                    label="Previous"
                    flags="progressive"
                    :framed="false"
                    :disabled="StateStore.prevDisabled"
                    @click="prev"
                    v-show="StateStore.data.step !== 'tutorial' && StateStore.data.isPublishing === false"
                />

                <WizardButton
                    id="mwe-rw-next"
                    icon="next"
                    label="Next"
                    flags="progressive primary"
                    :disabled="StateStore.nextDisabled"
                    @click="next"
                    v-show="StateStore.data.isBrowserReady && StateStore.data.step !== 'publish'"
                />

<!--                <WizardButton-->
<!--                    id="mwe-rw-publish"-->
<!--                    icon="upload"-->
<!--                    label="Publish"-->
<!--                    flags="progressive primary"-->
<!--                    :disabled="StateStore.hasPendingRequests"-->
<!--                    @click="next"-->
<!--                    v-show="StateStore.data.step === 'publish' && (StateStore.data.isPublishing === false || StateStore.hasPendingRequests === true)"-->
<!--                />-->

                <WizardButton
                    id="mwe-rw-restart"
                    icon="next"
                    label="Restart"
                    flags="progressive primary"
                    @click="next"
                    v-show="StateStore.data.isPublishing === true && !StateStore.hasPendingRequests"
                />

                <WizardButton
                    id="mwe-rw-retry"
                    icon="reload"
                    label="Retry"
                    @click="retry"
                    v-show="StateStore.showRetry"
                />
            </div>

            <div v-if="!StateStore.data.isContentVisible" id="mwe-rw-spinner">
                <img class="loading" src="/img/wait.svg" alt="Loading"/>
            </div>

            <div v-if="StateStore.data.isContentVisible" id="mwe-rw-content">
                <div class="mwe-rw-content" id="mwe-rw-tutorial" v-if="StateStore.data.step === 'testing'">
                    <Testing/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-speaker" v-if="StateStore.data.step === 'speaker'">
                    <Speaker/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-queue" v-if="StateStore.data.step === 'queue'">
                    <Queue/>
                </div>
                <div class="mwe-rw-content" id="rw-studio" v-if="StateStore.data.step === 'studio'">
                    <Studio/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-review" v-if="StateStore.data.step === 'finish'">
                    <Finish/>
                </div>
            </div>
        </div>
    </div>
</template>
