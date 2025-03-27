<script setup>

import {useNavigationStore} from "../../stores/NavigationStore.js";

const NavigationStore = useNavigationStore()
</script>

<template>
    <div class="theme-picker-wrapper">
        <div class="modal-heading">theme</div>
        <div class="theme-picker-option" :class="theme === NavigationStore.activeTheme ? 'active' : ''"
             v-for="theme in NavigationStore.themes" :key="theme"
             @click="NavigationStore.updateTheme(theme)"
        >
            <div class="theme-name">{{ theme }}</div>
            <div class="theme-preview" :class="theme">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</template>
