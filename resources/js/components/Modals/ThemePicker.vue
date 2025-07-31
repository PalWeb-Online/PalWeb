<script setup>
import {useNavigationStore} from "../../stores/NavigationStore.js";
import {computed} from "vue";

const NavigationStore = useNavigationStore();

const description = computed(() => {
    switch (NavigationStore.activeTheme) {
        case 'PalWebOS':
            return 'PalWeb\'s original theme. Modern & playful; for those who remember when the Internet used to be fun.';
        case 'Watermelon':
            return 'Juicy like its namesake. Refreshment for the eyes; a perfect counterbalance to hot summer days.';
        // case 'Nabatean':
        //     return 'Ancient stones the color of rust, sand as far as the eyes can see; an oasis in the distance.';
    }
})
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>theme picker</h1>
        </div>
        <div class="modal-container-body">
            <div class="theme-picker-option" :class="theme === NavigationStore.activeTheme ? 'active' : ''"
                 v-for="theme in NavigationStore.themes" :key="theme"
                 @click="NavigationStore.updateTheme(theme)"
            >
                {{ theme }}
            </div>
            <div class="window-section-head">
                <h2>preview</h2>
            </div>
            <div class="theme-preview">
                <div class="theme-preview-colors">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <p>{{ description }}</p>
            </div>
        </div>
    </div>
</template>
