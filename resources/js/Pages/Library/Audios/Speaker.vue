<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import AppTip from "../../../components/AppTip.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../stores/UserStore.js";
import UserItem from "../../../components/UserItem.vue";
import {usePaginator} from "../../../composables/usePaginator.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useSpeakerViewer} from "../../../composables/speakers/useSpeakerViewer.js";

const UserStore = useUserStore();
defineOptions({layout: Layout});

const props = defineProps({
    speakerId: {
        type: Number,
        required: true,
    },
});

const {
    speaker,
    speakerNotFound,
    isLoadingSpeaker,
    audios,
    audioPagination,
    reloadSpeaker,
} = useSpeakerViewer();

const fetchSpeakerPage = async (params = {}) => {
    await reloadSpeaker(props.speakerId, {
        page: params.page ?? 1,
    });
};

const {currentPage, pageNumbers, goToPage, updatePagination} = usePaginator(fetchSpeakerPage);

onMounted(() => {
    const searchParams = new URLSearchParams(window.location.search);
    fetchSpeakerPage(Object.fromEntries(searchParams.entries()));
});

watch(audioPagination, (pagination) => {
    if (pagination) updatePagination(pagination);
});

watch(
    () => props.speakerId,
    () => reloadSpeaker(props.speakerId)
);
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

            <LoadingSpinner v-if="isLoadingSpeaker"/>
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
                        <p>{{ speaker.user.private ? 'This Speaker' : speaker.user.name }} has not uploaded any Audios
                            yet.</p>
                    </AppTip>
                </template>
            </template>
            <div v-else-if="speakerNotFound" class="loading-state"><p>Unable to load Speaker.</p></div>
        </div>
    </div>
</template>
