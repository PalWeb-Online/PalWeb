<script setup>
import Layout from "../../Shared/Layout.vue";
import CommentItem from "../../components/CommentItem.vue";

defineOptions({
    layout: Layout
})

defineProps({
    feedbackComments: Array,
})
</script>

<template>
    <Head title="to-Do List"/>
    <div id="app-head">
        <h1>Feedback</h1>
    </div>
    <div id="app-body">
        <div class="comment-list">
            <CommentItem v-for="comment in feedbackComments" :model="comment"/>
        </div>
    </div>
</template>
