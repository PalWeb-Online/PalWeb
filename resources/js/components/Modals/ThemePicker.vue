<script setup>

import {useNavigationStore} from "../../stores/NavigationStore.js";

const NavigationStore = useNavigationStore()
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>theme</h1>
        </div>
        <div class="modal-container-body">
            <div class="theme-picker-option" :class="theme === NavigationStore.activeTheme ? 'active' : ''"
                 v-for="theme in NavigationStore.themes" :key="theme"
                 @click="NavigationStore.updateTheme(theme)"
            >
                {{ theme }}
            </div>
        </div>
    </div>
</template>
