<script setup>
import Layout from "../../Shared/Layout.vue";
import CommentItem from "../../components/CommentItem.vue";
import {onMounted, ref} from "vue";
import {route} from "ziggy-js";

defineOptions({
    layout: Layout
})

const feedbackComments = ref([]);

onMounted(async () => {
    const {data} = await axios.get(route('feedback.get'));
    feedbackComments.value = data.feedbackComments?.data ?? data.feedbackComments ?? [];
});
</script>

<template>
    <div id="app-head">
        <h1>Feedback</h1>
    </div>
    <div id="app-body">
        <div class="comment-list">
            <CommentItem v-for="comment in feedbackComments" :model="comment"/>
        </div>
    </div>
</template>
