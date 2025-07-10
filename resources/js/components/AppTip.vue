<script setup>
defineProps({
    question: {type: String, default: ''}
})
</script>

<template>
    <div class="app-tip">
        <div v-if="question" class="tip-content-wrapper">
            <div class="material-symbols-rounded">help</div>
            <div class="tip-content">
                <p style="font-weight: 700">
                    {{ question }}
                </p>
            </div>
        </div>
        <div class="tip-content-wrapper">
            <div class="material-symbols-rounded">info</div>
            <div class="tip-content">
                <slot/>
            </div>
        </div>
    </div>
</template>
