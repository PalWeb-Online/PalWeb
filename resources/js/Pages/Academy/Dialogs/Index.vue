<script setup>
import Paginator from '../../../Shared/Paginator.vue';
import DialogItem from '../../../components/DialogItem.vue';
import {useResourceIndex} from '../../../composables/resources/useResourceIndex.js';
import PaginationPage from '../../../components/PaginationPage.vue';
import LoadingSpinner from '../../../Shared/LoadingSpinner.vue';

const {
    collection: dialogs,
    loading,
    currentPage,
    pageNumbers,
    isLoadingPaginator,
    goToPage,
} = useResourceIndex({
    routeBase: 'dialogs',
    defaultFilters: { sort: 'latest' },
});
</script>
<template>
    <div id="app-head">
        <RouterLink :to="{ name: 'dialogs.index' }"><h1>{{ $t('pages.dialogs.index.title') }}</h1></RouterLink>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="loading"/>
        <div v-else class="app-body-section">
<!--            todo: occasional flash when switching pages-->
            <PaginationPage :is-loading="isLoadingPaginator">
                <DialogItem v-for="dialog in dialogs.data" :key="dialog.id" :model="dialog"/>
            </PaginationPage>
            <Paginator
                :pageNumbers="pageNumbers"
                :currentPage="currentPage"
                :goToPage="goToPage"
            />
        </div>
    </div>
</template>
