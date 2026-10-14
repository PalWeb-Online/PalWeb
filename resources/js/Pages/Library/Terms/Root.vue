<script setup>
import Layout from "../../../Shared/Layout.vue";
import RootContainer from "../../../components/RootContainer.vue";
import {onMounted, ref} from "vue";
import {route} from "ziggy-js";

defineOptions({
    layout: Layout
});

const props = defineProps({
    rootId: Number,
});

const root = ref(null);

onMounted(async () => {
    const {data} = await axios.get(route('api.roots.fetch', props.rootId));
    root.value = data.root;
});
</script>
<template>
    <Head :title="root ? `Dictionary: ${root.ar} (${root.en})` : 'Dictionary'"/>
    <div id="app-body">
        <RootContainer v-if="root" :root="root"/>
    </div>
</template>
