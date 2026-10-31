<script setup>
import CommentItem from "../../components/CommentItem.vue";
import {onMounted, ref} from "vue";

const feedbackComments = ref([]);

onMounted(async () => {
    const {data} = await axios.get('/api/feedback');
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
