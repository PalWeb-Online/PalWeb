<script setup>
import {computed} from "vue";

const props = defineProps({
    block: {type: Object, required: true},
})

const prompts = computed(() => props.block.prompts ?? []);

const instructionText = computed(() => {
    const custom = prompts.value.find(p => p.type === "text")?.value;
    if (custom && custom.trim().length) return custom;

    switch (props.block?.exerciseType) {
        case "input":
            return "Answer the prompt with a complete sentence, following the format of the example.";
        case "match":
            return "Match the two columns based on the prompt. Only one distribution of answers is possible.";
        case "select":
        default:
            return "Select the most appropriate answer in response to the prompt.";
    }
});
</script>

<template>
    <p>{{ instructionText }}</p>
    <div class="block-prompt--images">
        <img v-for="image in prompts.filter(p => p.type === 'image')" :key="image.id"
             :src="image.value" alt="Reference Image">
    </div>
    <div v-if="prompts.some(p => p.type === 'audio')" class="exercise-prompt_build">
        <audio v-for="audio in prompts.filter(p => p.type === 'audio')" :key="audio.id"
               :src="audio.value" controls/>
    </div>
</template>
