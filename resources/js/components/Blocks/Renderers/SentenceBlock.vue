<script setup>
defineProps({
    sentence: {type: Object, required: true}
})
</script>

<template>
    <div class="model-item-container sentence-item-container">
        <div class="model-item sentence-item">
            <div class="model-item-content">
                <div class="sentence-term" v-for="term in sentence.terms">
                    <div>{{ term.term }}</div>
                    <div>{{ term.transc }}</div>
                </div>
            </div>
        </div>
        <div class="model-item-description">
            {{ sentence.transl }}
        </div>
    </div>
</template>
