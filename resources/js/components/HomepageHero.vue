<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from "vue";
import AppLogo from "../Shared/AppLogo.vue";
import {useI18n} from "vue-i18n";
import {usePage} from "@inertiajs/vue3";

const { t } = useI18n();

const page = usePage();

const fullText = ref(t('meta.app.subtitle'));
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

watch(
    () => page.props.locale,
    (newLocale) => {
        if (newLocale) {
            fullText.value = t('meta.app.subtitle');
            displayedText.value = "";
            startTypingEffect();
        }
    }
);
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
