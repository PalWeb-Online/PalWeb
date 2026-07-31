<script setup>
import {computed} from "vue";
import {useI18n} from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    block: {type: Object, required: true},
})

const prompts = computed(() => props.block.prompts ?? []);

const instructionText = computed(() => {
    const custom = prompts.value.find(p => p.type === "text")?.value;
    if (custom && custom.trim().length) return custom;

    switch (props.block?.exerciseType) {
        case "input":
            return t('exercise.prompts.input');
        case "match":
            return t('exercise.prompts.match');
        case "sort":
            return t('exercise.prompts.sort');
        case "select":
        default:
            return t('exercise.prompts.select');
    }
});
</script>

<template>
    <p>{{ instructionText }}</p>
    <div class="block-prompt-images">
        <img v-for="image in prompts.filter(p => p.type === 'image')" :key="image.id"
             :src="image.value" alt="Reference Image">
    </div>
    <div v-if="prompts.some(p => p.type === 'audio')" class="exercise-prompt_build">
        <audio v-for="audio in prompts.filter(p => p.type === 'audio')" :key="audio.id"
               :src="audio.value" controls/>
    </div>
</template>

<style scoped lang="scss">
.block-prompt-images {
    display: grid;
    gap: 1.6rem;
    margin-block: 1.6rem;
    grid-template-columns: repeat(auto-fit, minmax(24rem, 1fr));
}
</style>
