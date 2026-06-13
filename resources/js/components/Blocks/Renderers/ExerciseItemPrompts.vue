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
                        <span v-if="isViewingResults && exercise.type !== 'match'" class="material-symbols-rounded"
                              :class="{ 'correct': exercise.correct }">
                            {{ exercise.correct ? 'check_circle' : 'cancel' }}
                        </span>
            <p>{{ text.value }}</p>
        </div>
    </template>
</template>

<style scoped lang="scss">
.exercise-prompt_render {
    display: flex;
    align-items: center;
    gap: 1.6rem;

    .material-symbols-rounded {
        font-size: 3.2rem;
        color: var(--color-medium-primary);
        user-select: none;

        &.correct {
            color: var(--color-medium-secondary);
        }
    }

    p {
        font-family: var(--ar-body-font);
        font-size: 2.0rem;
        margin: 0;
        background: white;
        border-radius: 1.6rem;
        box-shadow: 0 0.8rem 0 var(--color-accent-light);
        padding: 1.2rem 1.6rem;
        margin-block-end: 0.8rem;
    }
}
</style>
