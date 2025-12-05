<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";
import AppLogo from "../Shared/AppLogo.vue";

const fullText = ref("the Web of Palestinian Arabic!");
const displayedText = ref("");
const cursorOpacity = ref(1);

const typingSpeed = 50;
const blinkSpeed = 500;

let typingInterval = null;
let cursorInterval = null;

const startTypingEffect = () => {
    let currentIndex = 0;

    typingInterval = setInterval(() => {
        if (currentIndex < fullText.value.length) {
            displayedText.value += fullText.value[currentIndex];
            currentIndex++;
        } else {
            clearInterval(typingInterval);
        }
    }, typingSpeed);
};

const startCursorBlinking = () => {
    cursorInterval = setInterval(() => {
        cursorOpacity.value = cursorOpacity.value === 1 ? 0 : 1;
    }, blinkSpeed);
};

onMounted(async () => {
    startTypingEffect();
    startCursorBlinking();
});

onBeforeUnmount(() => {
    clearInterval(typingInterval);
    clearInterval(cursorInterval);
});
</script>
<template>
    <div class="app-logo-wrapper">
        <AppLogo/>
        <h1>
            <span>{{ displayedText }}</span>
            <span class="typing-cursor"
                  :style="{ opacity: cursorOpacity }"
            >|</span>
        </h1>
    </div>
</template>
