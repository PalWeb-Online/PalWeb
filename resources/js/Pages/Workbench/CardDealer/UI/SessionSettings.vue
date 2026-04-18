<script setup>
import {route} from "ziggy-js";
import RangeSlider from "../../../../components/RangeSlider.vue";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useUserStore} from "../../../../stores/UserStore.js";
import WindowSection from "../../../../components/WindowSection.vue";
import ToggleMulti from "../../../../components/ToggleMulti.vue";
import AppTip from "../../../../components/AppTip.vue";

const NotificationStore = useNotificationStore();
const UserStore = useUserStore();

const props = defineProps({
    settings: Object
})

const saveSettings = async () => {
    console.log('attempting save');

    if (props.settings.new_limit > props.settings.review_limit) {
        props.settings.review_limit = props.settings.new_limit;
    }

    const response = await axios.patch(route('users.preferences.update', UserStore.user.username), {
        srs_settings: {
            new_limit: props.settings.new_limit,
            review_limit: props.settings.review_limit,
            learning_steps: props.settings.learning_steps,
            prompt_type: props.settings.prompt_type,
        }
    });

    if (response.data.success) {
        UserStore.user.preferences.srs = response.data.preferences.srs;
        NotificationStore.addNotification('Preferences have been updated!', 'success')
    }
}
</script>

<template>
    <WindowSection :visible="false">
        <template #title>
            <h1>settings</h1>
        </template>
        <template #content>
            <section class="session-settings">
                <div class="settings-wrapper" style="background: white">
                    <RangeSlider
                        v-model="settings.new_limit"
                        label="New Limit"
                        min="0" max="50"
                        @change="saveSettings"
                    />
                    <RangeSlider
                        v-model="settings.review_limit"
                        label="Review Limit"
                        min="25" max="300"
                        @change="saveSettings"
                    />
                    <RangeSlider
                        v-model="settings.learning_steps"
                        label="Learning Steps"
                        min="1" max="5"
                        @change="saveSettings"
                    />
                    <ToggleMulti v-model="settings.prompt_type"
                                 label="Prompt Type"
                                 :options="['term', 'gloss', 'audio']"
                                 @change="saveSettings"
                    />
                </div>

                <AppTip v-if="settings.prompt_type === 'audio'">
                    <p>Only Terms with an Audio by a Speaker with a fluency level of "Fluent" or "Native" will be
                        included in the session, so you may not see all Terms that are actually due. If after the
                        session you still have Due Cards, you may need to choose a Prompt Type other than "Audio"
                        to see them all.</p>
                </AppTip>
            </section>
        </template>
    </WindowSection>
</template>
