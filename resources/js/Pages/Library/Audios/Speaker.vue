<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import AppTip from "../../../components/AppTip.vue";
import { route } from "ziggy-js";
import { useUserStore } from "../../../stores/UserStore.js";
import UserItem from "../../../components/UserItem.vue";

const UserStore = useUserStore();
defineOptions({ layout: Layout });

const speaker = ref(null);
const audios  = ref(null);
const loading = ref(true);

async function fetchSpeaker() {
    loading.value = true;
    try {
        const id = window.location.pathname.split('/').pop();
        const response = await fetch(`/api/library/audios/${id}`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        if (!response.ok) {
            console.error('Failed to fetch speaker, status:', response.status);
            return;
        }
        const data = await response.json();
        speaker.value = data.speaker;
        audios.value  = data.audios;
    } catch (error) {
        console.error('Failed to fetch speaker:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchSpeaker());
</script>

<template>
    <Head :title="speaker ? `Library: Audios by ${speaker.user.name}` : 'Library: Audios'"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('audios.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/audios/{speaker}</div>
            </div>
            <div class="window-section-head"><h1>speaker</h1></div>

            <div v-if="loading" class="loading-state"><p>Loading...</p></div>

            <template v-else-if="speaker">
                <AppTip v-if="speaker.user.id === UserStore.user?.id && speaker.user.private">
                    <p>Your Profile is currently set to Private. Others may still interact with any Audios you have
                        recorded, but your Speaker profile is anonymous & does not link to your user account.</p>
                </AppTip>
                <UserItem :user="speaker.user" size="l" :speaker="speaker">
                    <SpeakerItem :speaker="speaker"/>
                </UserItem>
                <div class="window-section-head"><h2>audios</h2></div>

                <template v-if="audios && audios.data && audios.data.length > 0">
                    <div class="model-list index-list">
                        <PronunciationItem
                            v-for="audio in audios.data"
                            :model="audio.pronunciation"
                            :audio="audio"
                        />
                    </div>
                    <Paginator :links="audios.meta.links"/>
                </template>
                <template v-else>
                    <AppTip>
                        <p>{{ speaker.user.private ? 'This Speaker' : speaker.user.name }} has not uploaded any Audios yet.</p>
                    </AppTip>
                </template>
            </template>

            <template v-else>
                <AppTip><p>Speaker not found.</p></AppTip>
            </template>
        </div>
    </div>
</template>