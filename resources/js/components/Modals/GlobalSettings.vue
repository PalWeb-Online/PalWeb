<script setup>
import {useNavigationStore} from "../../stores/NavigationStore.js";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {computed, ref} from "vue";
import {useUserStore} from "../../stores/UserStore.js";
import ToggleSingle from "../ToggleSingle.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useI18n} from "vue-i18n";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();
const NotificationStore = useNotificationStore();

const description = computed(() => {
    switch (NavigationStore.activeTheme) {
        case 'PalWebOS':
            return 'PalWeb\'s original theme. Modern & playful; for those who remember when the Internet used to be fun.';
        case 'Watermelon':
            return 'Juicy like its namesake. Refreshment for the eyes; a perfect counterbalance to hot summer days.';
        case 'Nabatean':
            return 'Ancient stones the color of rust, sand as far as the eyes can see; an oasis in the distance.';
        case 'Jerusalem':
            return 'Intricate tilework of turquoise & lapis lazuli; homes protected from winter by the eponymous stone.';
        case 'Falastin':
            return 'Most influential newspaper in Ottoman & Mandatory Palestine, a symbol of Palestinian national identity.';
        // case 'Summac':
        //     return 'Chicken, spices & caramelized onion.';
    }
});

const {locale} = useI18n();
const selectedLocale = ref(UserStore.user?.language ?? 'en');
const availableLocales = [
    {
        value: 'en',
        label: 'English',
    },
    {
        value: 'es',
        label: 'Español',
    },
    {
        value: 'ar',
        label: 'العربية',
    }
];

const changeLanguage = (lang) => {
    locale.value = lang;

    router.post(route('language.store', lang), {}, {
        preserveScroll: true,
    });
};

const toggleNotifications = async (nextValue) => {
    try {
        await NotificationStore.toggleBrowserSubscription(nextValue);
    } catch (error) {
        console.error(error);
        alert(error.message || (nextValue ? 'Could not enable notifications.' : 'Could not disable notifications.'));
    }
};
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>settings</h1>
        </div>

        <div class="modal-container-body form-body">
            <div class="field-item">
                <label>Theme</label>
                <select
                    v-model="NavigationStore.activeTheme"
                    @change="NavigationStore.updateTheme(NavigationStore.activeTheme)"
                >
                    <option v-for="theme in NavigationStore.themes" :key="theme" :value="theme">
                        {{ theme }}
                    </option>
                </select>
            </div>
<!--            <div class="field-item">-->
<!--                <label>Language</label>-->
<!--                <select-->
<!--                    v-model="selectedLocale"-->
<!--                    @change="changeLanguage(selectedLocale)"-->
<!--                >-->
<!--                    <option v-for="(locale, i) in availableLocales" :key="i" :value="locale.value">-->
<!--                        {{ locale.label }}-->
<!--                    </option>-->
<!--                </select>-->
<!--            </div>-->
            <ToggleSingle v-if="UserStore.isUser"
                          :model-value="NotificationStore.currentBrowserSubscribed"
                          @update:modelValue="toggleNotifications"
                          label="notifications"
            />
        </div>

        <div class="window-section-head">
            <h2>theme preview</h2>
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
</template>
