<script setup>
import {computed} from 'vue';
import AppTip from "../../../../components/AppTip.vue";

const props = defineProps({
    block: {type: Object, required: true}
});

const paragraphs = computed(() => {
    if (!props.block.content) return [];

    return props.block.content.split(/\n+/).map(p => {
        return p.replace(/\*([^*]+)\*/g, '<b>$1</b>');
    });
});
</script>

<template>
    <AppTip v-if="block.tip">
        <p v-for="(p, i) in paragraphs" :key="i" v-html="p"></p>
    </AppTip>

    <div v-else class="block--text">
        <p v-for="(p, i) in paragraphs" :key="i" v-html="p"></p>
    </div>
</template>
