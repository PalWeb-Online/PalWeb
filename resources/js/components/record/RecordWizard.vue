<script setup>
import { onBeforeUnmount, onMounted } from 'vue';
import { useSidebarStore } from './store/useSidebarStore';
import { useNavigationStore } from './store/useNavigationStore';
import RequestQueue from '../../utils/RequestQueue.js';
import Record from '../../utils/Record';
import Tutorial from './pages/Tutorial.vue';
import Speaker from './pages/Speaker.vue';
import Details from './pages/Details.vue';
import Studio from './pages/Studio.vue';
import Publish from './pages/Publish.vue';
import WizardButton from './ui/WizardButton.vue';

const { state: sidebarState } = useSidebarStore();
const {
    prevDisabled,
    nextDisabled,
    showRetry,
    prev,
    next,
    cancel,
    retry,
    hasPendingRequests,
    openFileList,
} = useNavigationStore();

// Request queue initialization (if necessary)
const requestQueue = new RequestQueue();

const preventWindowClose = (event) => {
    if (hasPendingRequests()) {
        event.preventDefault();
        event.returnValue = ''; // Standard way to trigger confirmation dialog in browsers
    }
};

// Lifecycle hook to simulate loading completion and set up window event listeners
onMounted(() => {
    setTimeout(() => {
        sidebarState.isContentVisible = true;
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
    <div>
        <h2>Record Wizard</h2>
        <div id="mwe-rw">
            <!-- Step Navigation -->
            <ul id="mwe-rw-steps">
                <li :class="{ active: sidebarState.step === 'tutorial' }">1. Tutorial</li>
                <li :class="{ active: sidebarState.step === 'speaker' }">2. Speaker</li>
                <li :class="{ active: sidebarState.step === 'details' }">3. Details</li>
                <li :class="{ active: sidebarState.step === 'studio' }">4. Studio</li>
                <li :class="{ active: sidebarState.step === 'publish' }">5. Publish</li>
            </ul>

            <div id="mwe-rw-main">
                <!-- Spinner (shown until content is ready) -->
                <div v-if="!sidebarState.isContentVisible" id="mwe-rw-spinner">
                    <img src="/path/to/spinner.svg" alt="Loading..." />
                </div>

                <!-- Main Content (shown after loading) -->
                <div v-if="sidebarState.isContentVisible" id="mwe-rw-content">
                    <div class="mwe-rw-content" id="mwe-rw-tutorial" v-if="sidebarState.step === 'tutorial'">
                        <Tutorial />
                    </div>
                    <div class="mwe-rw-content" id="mwe-rw-speaker" v-if="sidebarState.step === 'speaker'">
                        <Speaker />
                    </div>
                    <div class="mwe-rw-content" id="mwe-rw-details" v-if="sidebarState.step === 'details'">
                        <Details />
                    </div>
                    <div class="mwe-rw-content" id="mwe-rw-studio" v-if="sidebarState.step === 'studio'">
                        <Studio />
                    </div>
                    <div class="mwe-rw-content" id="mwe-rw-publish" v-if="sidebarState.step === 'publish'">
                        <Publish />
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div id="mwe-rw-navigation">
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
                        :disabled="prevDisabled"
                        @click="prev"
                        v-show="sidebarState.step !== 'tutorial' && sidebarState.isPublishing === false"
                    />

                    <WizardButton
                        id="mwe-rw-next"
                        icon="next"
                        label="Next"
                        flags="progressive primary"
                        :disabled="nextDisabled"
                        @click="next"
                        v-show="sidebarState.isBrowserReady && sidebarState.step !== 'publish'"
                    />

                    <WizardButton
                        id="mwe-rw-publish"
                        icon="upload"
                        label="Publish"
                        flags="progressive primary"
                        :disabled="hasPendingRequests"
                        @click="next"
                        v-show="sidebarState.step === 'publish' && (sidebarState.isPublishing === false || hasPendingRequests === true)"
                    />

                    <WizardButton
                        id="mwe-rw-restart"
                        icon="next"
                        label="Restart"
                        flags="progressive primary"
                        @click="next"
                        v-show="sidebarState.isPublishing === true && !hasPendingRequests"
                    />

                    <WizardButton
                        id="mwe-rw-commonsfilelist"
                        icon="logoWikimediaCommons"
                        label="Open Commons File List"
                        flags="progressive"
                        :framed="false"
                        @click="openFileList"
                        v-show="sidebarState.isPublishing === true"
                    />

                    <WizardButton
                        id="mwe-rw-retry"
                        icon="reload"
                        label="Retry"
                        @click="retry"
                        v-show="showRetry"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.mwe-rw-content-title {
    margin-bottom: 20px;
}

.mwe-rw-section-group {
    margin-top: 20px;
}

#mwe-rw-steps {
    list-style: none;
    display: flex;
    justify-content: space-between;
}

#mwe-rw-navigation {
    margin-top: 20px;
}

.wizard-button {
    margin: 5px;
}

.sidebar ul {
    list-style: none;
}

.sidebar li.active {
    font-weight: bold;
}
</style>
