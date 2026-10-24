<script setup>
import RootContainer from "../../../components/RootContainer.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    rootId: Number,
});

const root = ref(null);

onMounted(async () => {
    const {data} = await axios.get(`/api/roots/${props.rootId}`);
    root.value = data.root;
});
</script>
<template>
    <div id="app-body">
        <RootContainer v-if="root" :root="root"/>
    </div>
</template>
