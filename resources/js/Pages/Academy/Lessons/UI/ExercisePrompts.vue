<script setup>
defineProps({
    exercise: Object,
    isViewingResults: Boolean
})
</script>

<template>
    <div v-if="exercise.prompts.some(p => p.type === 'image')" class="exercise-prompt_build">
        <img v-for="image in exercise.prompts.filter(p => p.type === 'image')" :key="image.id"
             :src="image.value" alt="Reference Image">
    </div>
    <div v-if="exercise.prompts.some(p => p.type === 'audio')" class="exercise-prompt_build">
        <audio v-for="audio in exercise.prompts.filter(p => p.type === 'audio')" :key="audio.id"
               :src="audio.value" controls/>
    </div>
    <template v-if="exercise.prompts.some(p => p.type === 'text')">
        <div v-for="text in exercise.prompts.filter(p => p.type === 'text')"
             class="exercise-prompt_render">
                        <span v-if="isViewingResults" class="material-symbols-rounded"
                              :class="{ 'correct': exercise.correct }">
                            {{ exercise.correct ? 'check_circle' : 'cancel' }}
                        </span>
            <p>{{ text.value }}</p>
        </div>
    </template>
</template>

<style scoped>

</style>
