<script setup>
import {onMounted, watch} from 'vue';
import PronunciationItem from '../../../components/PronunciationItem.vue';
import Paginator from '../../../Shared/Paginator.vue';
import SpeakerItem from '../../../components/SpeakerItem.vue';
import AppTip from '../../../components/AppTip.vue';
import {useUserStore} from '../../../stores/UserStore.js';
import UserItem from '../../../components/UserItem.vue';
import {usePaginator} from '../../../composables/usePaginator.js';
import {useSpeakerViewer} from '../../../composables/speakers/useSpeakerViewer.js';
import {useRoute} from 'vue-router';
import PaginationPage from '../../../components/PaginationPage.vue';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const UserStore = useUserStore();

const route = useRoute();
const currentQuery = () => ({ ...route.query });

const props = defineProps({
    speakerId: {
        type: Number,
        required: true,
    },
});

const {
    speaker,
    isLoadingSpeaker,
    audios,
    reloadSpeaker,
    fetchSpeakerAudios,
} = useSpeakerViewer();

const fetchSpeakerPage = async (params = {}) => {
    await reloadSpeaker(props.speakerId, { params });
};

const fetchAudioPage = async (params = {}) => {
    isLoadingPaginator.value = true;

    try {
        await fetchSpeakerAudios(props.speakerId, params);
    } finally {
        isLoadingPaginator.value = false;
    }
};

const {
    currentPage,
    pageNumbers,
    goToPage,
    updatePagination,
    isLoadingPaginator,
} = usePaginator(fetchAudioPage);

onMounted(() => fetchSpeakerPage(currentQuery()));

watch(() => audios.value.meta, (pagination) => {
    updatePagination(pagination);
});

watch(
    () => [props.speakerId, route.query.page],
    ([speakerId], [oldSpeakerId]) => {
        if (speakerId !== oldSpeakerId) {
            fetchSpeakerPage(currentQuery());
            return;
        }

        fetchAudioPage(currentQuery());
    },
);
</script>

<template>
    <LoadingWrapper :is-loading="isLoadingSpeaker || !speaker">
        <div id="app-body">
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
                <template v-if="audios.data.length > 0">
                    <PaginationPage :is-loading="isLoadingPaginator">
                        <PronunciationItem
                            v-for="audio in audios.data"
                            :key="audio.id"
                            :model="audio.pronunciation"
                            :audio="audio"
                        />
                    </PaginationPage>
                    <Paginator
                        :pageNumbers="pageNumbers"
                        :currentPage="currentPage"
                        :goToPage="goToPage"
                    />
                </template>
                <template v-else>
                    <AppTip>
                        <p>{{
                                $t('pages.audios.speaker.empty', { speaker: speaker.user.private ? 'This Speaker' : speaker.user.name })
                            }}</p>
                    </AppTip>
                </template>
            </div>
        </div>
    </LoadingWrapper>
</template>
