<script setup>
import {onBeforeUnmount, onMounted} from 'vue';
import useStateStore from './store/useStateStore.js';
import useNavigationStore from './store/useNavigationStore';
import RequestQueue from '../../utils/RequestQueue.js';
import Tutorial from './pages/Tutorial.vue';
import Speaker from './pages/Speaker.vue';
import Details from './pages/Details.vue';
import Studio from './pages/Studio.vue';
import Publish from './pages/Publish.vue';
import WizardButton from './ui/WizardButton.vue';

const stateStore = useStateStore();
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
    stateStore.data.isContentVisible = true;
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
    <!-- Step Navigation -->
    <div id="mwe-rw-steps">
      <div :class="{ active: stateStore.data.step === 'tutorial' }">Tutorial</div>
      <div :class="{ active: stateStore.data.step === 'speaker' }">Speaker</div>
      <div :class="{ active: stateStore.data.step === 'details' }">Details</div>
      <div :class="{ active: stateStore.data.step === 'studio' }">Studio</div>
      <div :class="{ active: stateStore.data.step === 'publish' }">Publish</div>
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
            :disabled="prevDisabled"
            @click="prev"
            v-show="stateStore.data.step !== 'tutorial' && stateStore.data.isPublishing === false"
        />

        <WizardButton
            id="mwe-rw-next"
            icon="next"
            label="Next"
            flags="progressive primary"
            :disabled="nextDisabled"
            @click="next"
            v-show="stateStore.data.isBrowserReady && stateStore.data.step !== 'publish'"
        />

        <WizardButton
            id="mwe-rw-publish"
            icon="upload"
            label="Publish"
            flags="progressive primary"
            :disabled="hasPendingRequests"
            @click="next"
            v-show="stateStore.data.step === 'publish' && (stateStore.data.isPublishing === false || hasPendingRequests === true)"
        />

        <WizardButton
            id="mwe-rw-restart"
            icon="next"
            label="Restart"
            flags="progressive primary"
            @click="next"
            v-show="stateStore.data.isPublishing === true && !hasPendingRequests"
        />

        <WizardButton
            id="mwe-rw-commonsfilelist"
            icon="logoWikimediaCommons"
            label="Open Commons File List"
            flags="progressive"
            :framed="false"
            @click="openFileList"
            v-show="stateStore.data.isPublishing === true"
        />

        <WizardButton
            id="mwe-rw-retry"
            icon="reload"
            label="Retry"
            @click="retry"
            v-show="showRetry"
        />
      </div>

      <div v-if="!stateStore.data.isContentVisible" id="mwe-rw-spinner">
        <img src="/img/wait.svg" alt="Loading"/>
      </div>

      <div v-if="stateStore.data.isContentVisible" id="mwe-rw-content">
        <div class="mwe-rw-content" id="mwe-rw-tutorial" v-if="stateStore.data.step === 'tutorial'">
          <Tutorial/>
        </div>
        <div class="mwe-rw-content" id="mwe-rw-speaker" v-if="stateStore.data.step === 'speaker'">
          <Speaker/>
        </div>
        <div class="mwe-rw-content" id="mwe-rw-details" v-if="stateStore.data.step === 'details'">
          <Details/>
        </div>
        <div class="mwe-rw-content" id="mwe-rw-studio" v-if="stateStore.data.step === 'studio'">
          <Studio/>
        </div>
        <div class="mwe-rw-content" id="mwe-rw-publish" v-if="stateStore.data.step === 'publish'">
          <Publish/>
        </div>
      </div>
    </div>
  </div>
</template>
