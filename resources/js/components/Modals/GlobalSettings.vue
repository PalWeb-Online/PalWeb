<script setup>
import {useNavigationStore} from '../../stores/NavigationStore.js';
import {useNotificationStore} from '../../stores/NotificationStore.js';
import {computed, ref} from 'vue';
import {useUserStore} from '../../stores/UserStore.js';
import ToggleSingle from '../ToggleSingle.vue';
import {useI18n} from 'vue-i18n';
import i18n from '../../i18n.js';
import {changeApplicationLocale} from '../../runtime/preferences.js';

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();
const NotificationStore = useNotificationStore();

const {
    t,
    locale,
} = useI18n();

const description = computed(() => t('modals.settings.theme-description.' + NavigationStore.activeTheme));

const selectedLocale = ref(locale.value ?? 'en');
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
        label: 'العربيّة',
    },
];

const selectLocale = async (nextLocale) => {
    selectedLocale.value = await changeApplicationLocale(i18n, nextLocale);
};

const toggleNotifications = async (nextValue) => {
    try {
        await NotificationStore.toggleBrowserSubscription(nextValue);
    } catch (error) {
        console.error(error);
        alert(error.message || t(nextValue
            ? 'modals.settings.notifications.enable-error'
            : 'modals.settings.notifications.disable-error',
        ));
    }
};
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>{{ $t('modals.settings.title') }}</h1>
        </div>

        <div class="modal-container-body form-body">
            <div class="field-item">
                <label>{{ $t('modals.settings.fields.color-theme') }}</label>
                <select
                    v-model="NavigationStore.activeTheme"
                    @change="NavigationStore.updateTheme(NavigationStore.activeTheme)"
                >
                    <option v-for="theme in NavigationStore.themes" :key="theme" :value="theme">
                        {{ theme }}
                    </option>
                </select>
            </div>
            <div class="field-item">
                <label>{{ $t('modals.settings.fields.language') }}</label>
                <select
                    v-model="selectedLocale"
                    @change="selectLocale(selectedLocale)"
                >
                    <option v-for="(loc, i) in availableLocales" :key="i" :value="loc.value">
                        {{ loc.label }}
                    </option>
                </select>
            </div>
            <ToggleSingle v-if="UserStore.isUser"
                          :model-value="NotificationStore.currentBrowserSubscribed"
                          @update:modelValue="toggleNotifications"
                          :label="$t('modals.settings.fields.notifications')"
            />
        </div>

        <div class="window-section-head">
            <h2>{{ $t('modals.settings.theme-preview') }}</h2>
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
