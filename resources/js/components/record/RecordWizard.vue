<script setup>
import {onBeforeUnmount, onMounted} from 'vue';
import {useStateStore} from './store/StateStore';
import {useNavigationStore} from './store/NavigationStore';
import Tutorial from './pages/Tutorial.vue';
import Speaker from './pages/Speaker.vue';
import Queue from './pages/Queue.vue';
import Record from './pages/Record.vue';
import Publish from './pages/Publish.vue';
import WizardButton from './ui/WizardButton.vue';

const StateStore = useStateStore();
const {
    prev,
    next,
    cancel,
    retry,
} = useNavigationStore();

const preventWindowClose = (event) => {
    if (StateStore.hasPendingRequests) {
        event.preventDefault();
        event.returnValue = ''; // Standard way to trigger confirmation dialog in browsers
    }
};

// Lifecycle hook to simulate loading completion and set up window event listeners
onMounted(() => {
    setTimeout(() => {
        StateStore.data.isContentVisible = true;
    }, 1000);

    // Prevent window close if there are pending requests
    window.addEventListener('beforeunload', preventWindowClose);
});

// Clean up the window event listener before component is destroyed
onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', preventWindowClose);
});
</script>

<template>
    <h1>Record Wizard</h1>
    <div id="mwe-rw">
        <div id="mwe-rw-steps">
            <div :class="{ active: StateStore.data.step === 'tutorial' }">Tutorial</div>
            <div :class="{ active: StateStore.data.step === 'speaker' }">Speaker</div>
            <div :class="{ active: StateStore.data.step === 'queue' }">Queue</div>
            <div :class="{ active: StateStore.data.step === 'record' }">Record</div>
            <div :class="{ active: StateStore.data.step === 'publish' }">Publish</div>
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

                <WizardButton
                    id="mwe-rw-publish"
                    icon="upload"
                    label="Publish"
                    flags="progressive primary"
                    :disabled="StateStore.hasPendingRequests"
                    @click="next"
                    v-show="StateStore.data.step === 'publish' && (StateStore.data.isPublishing === false || StateStore.hasPendingRequests === true)"
                />

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
                <div class="mwe-rw-content" id="mwe-rw-tutorial" v-if="StateStore.data.step === 'tutorial'">
                    <Tutorial/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-speaker" v-if="StateStore.data.step === 'speaker'">
                    <Speaker/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-queue" v-if="StateStore.data.step === 'queue'">
                    <Queue/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-studio" v-if="StateStore.data.step === 'record'">
                    <Record/>
                </div>
                <div class="mwe-rw-content" id="mwe-rw-publish" v-if="StateStore.data.step === 'publish'">
                    <Publish/>
                </div>
            </div>
        </div>
    </div>
</template>
