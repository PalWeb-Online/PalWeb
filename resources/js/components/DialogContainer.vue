<script setup>
import {onMounted, reactive} from "vue";
import {route} from "ziggy-js";
import SentenceItem from "./SentenceItem.vue";

const props = defineProps({
    id: Number,
});

const data = reactive({
    dialog: {},
    isLoading: true
});

async function fetchDialog() {
    try {
        const response = await axios.get(route('dialogs.get', props.id));
        data.dialog = response.data.dialog;

        data.isLoading = false;

    } catch (error) {
        console.error("Error fetching Dialog:", error);
    }
}

onMounted(async () => {
    await fetchDialog();
});
</script>

<template>
    <template v-if="! data.isLoading">
        <div>{{ data.dialog.title }}</div>
        <div v-if="data.dialog.description">{{ data.dialog.description }}</div>
        <iframe v-if="data.dialog.media" src="{{ data.dialog.media }}" allowfullscreen></iframe>

        <div class="activity-dialog">
            <template v-for="sentence in data.dialog.sentences">
                <SentenceItem :id="sentence.id" size="m"/>
            </template>
        </div>
    </template>
</template>
