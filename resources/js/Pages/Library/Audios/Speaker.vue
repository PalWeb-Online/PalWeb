<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import AppTip from "../../../components/AppTip.vue";
import { route } from "ziggy-js";
import { useUserStore } from "../../../stores/UserStore.js";
import UserItem from "../../../components/UserItem.vue";
import { usePaginator } from "../../../composables/usePaginator.js";

const UserStore = useUserStore();
defineOptions({ layout: Layout });

const speaker = ref(null);
const audios  = ref([]);
const loading = ref(true);

const { currentPage, pageNumbers, goToPage, updatePagination } = usePaginator(fetchSpeaker);

async function fetchSpeaker(params = {}) {
    loading.value = true;
    try {
        const id = window.location.pathname.split('/').pop();
        const query = new URLSearchParams(params).toString();
        const response = await fetch(`/api/library/audios/${id}${query ? '?' + query : ''}`);
        const data = await response.json();
        speaker.value = data.speaker;

        if (Array.isArray(data.audios)) {
            audios.value = data.audios;
        } else if (data.audios && data.audios.data) {
            audios.value = data.audios.data;
            if (data.audios.meta) updatePagination(data.audios.meta);
        } else {
            audios.value = [];
        }
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
                    <p>Your Profile is currently set to Private.</p>
                </AppTip>
                <UserItem :user="speaker.user" size="l" :speaker="speaker">
                    <SpeakerItem :speaker="speaker"/>
                </UserItem>
                <div class="window-section-head"><h2>audios</h2></div>
                <template v-if="audios.length > 0">
                    <div class="model-list index-list">
                        <PronunciationItem
                            v-for="audio in audios"
                            :key="audio.id"
                            :model="audio.pronunciation"
                            :audio="audio"
                        />
                    </div>
                    <Paginator
                        :pageNumbers="pageNumbers"
                        :currentPage="currentPage"
                        :goToPage="goToPage"
                    />
                </template>
                <template v-else>
                    <AppTip>
                        <p>{{ speaker.user.private ? 'This Speaker' : speaker.user.name }} has not uploaded any Audios yet.</p>
                    </AppTip>
                </template>
            </template>
        </div>
    </div>
</template>