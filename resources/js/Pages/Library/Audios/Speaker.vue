<script setup>
import {onMounted, watch} from "vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import SpeakerItem from "../../../components/SpeakerItem.vue";
import AppTip from "../../../components/AppTip.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import UserItem from "../../../components/UserItem.vue";
import {usePaginator} from "../../../composables/usePaginator.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useSpeakerViewer} from "../../../composables/speakers/useSpeakerViewer.js";

const UserStore = useUserStore();

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
    <LoadingSpinner v-if="isLoadingSpeaker"/>
    <AppTip v-else-if="speakerNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.term')}) }}</p>
    </AppTip>
    <div v-else-if="speaker" id="app-body">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'audios.index' }" class="material-symbols-rounded">home</RouterLink>
                <div class="window-header-url">www.palweb.app/library/audios/{speaker}</div>
            </div>
            <div class="window-section-head"><h1>{{ $t('actions.models.speaker') }}</h1></div>

            <AppTip v-if="speaker.user.id === UserStore.user?.id && speaker.user.private">
                <p>{{ $t('pages.audios.speaker.is-private') }}</p>
            </AppTip>
            <UserItem :user="speaker.user" size="l" :speaker="speaker">
                <SpeakerItem :speaker="speaker"/>
            </UserItem>
            <div class="window-section-head"><h2>{{ $t('models.audios') }}</h2></div>
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
                    <p>{{
                            $t('pages.audios.speaker.empty', {speaker: speaker.user.private ? 'This Speaker' : speaker.user.name})
                        }}</p>
                </AppTip>
            </template>
        </div>
    </div>
</template>
