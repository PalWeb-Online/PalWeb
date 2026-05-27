<script setup>
import AppTip from "../../AppTip.vue";

defineProps({
    sentence: Object
})
</script>

<template>
    <div v-if="sentence" class="model-item-container sentence-item-container">
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
    <AppTip v-else>
        Empty Sentence Block. Please create a custom Sentence or load in a Sentence model.
    </AppTip>
</template>
