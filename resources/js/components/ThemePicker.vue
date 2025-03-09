<script setup>
import {ref} from 'vue';
import AppTooltip from "./AppTooltip.vue";

const themes = ['palweb', 'watermelon', 'jerusalem', 'zaatar', 'jericho'];

const activeTheme = ref(localStorage.getItem('selectedTheme') || 'palweb');

const tooltip = ref(null);

function handleMouseMove(event) {
    tooltip.value.showTooltip(activeTheme.value, event);
}

function handleMouseLeave() {
    tooltip.value.hideTooltip();
}

const updateTheme = (theme) => {
    document.body.classList.remove(...themes.map((t) => `theme-${t}`));
    document.body.classList.add(`theme-${theme}`);
    activeTheme.value = theme;
    localStorage.setItem('selectedTheme', theme);
};

updateTheme(activeTheme.value);
</script>

<template>
    <div class="theme-picker">
        <div class="featured-title m">Theme</div>
        <div class="theme-picker-theme" v-for="theme in themes" :key="theme">
            <div class="theme-picker-theme-name">{{ theme }}</div>
            <button :class="theme"
                    @mousemove="handleMouseMove($event)"
                    @mouseleave="handleMouseLeave"
                    @click="updateTheme(theme)">
                <div></div>
                <div></div>
                <div></div>
            </button>
        </div>
    </div>

    <AppTooltip ref="tooltip"/>
</template>
