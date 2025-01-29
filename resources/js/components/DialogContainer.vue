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
        <div class="dialog-container">
            <div class="dialog-container-head">
                <div class="dialog-container-head-title">{{ data.dialog.title }}</div>
            </div>
            <iframe v-if="data.dialog.media" :src="data.dialog.media" allowfullscreen></iframe>
            <div class="dialog-description" v-if="data.dialog.description">{{ data.dialog.description }}</div>

            <div class="dialog-body">
                <template v-for="sentence in data.dialog.sentences">
                    <SentenceItem :id="sentence.id" speaker/>
                </template>
            </div>
        </div>
    </template>
</template>
