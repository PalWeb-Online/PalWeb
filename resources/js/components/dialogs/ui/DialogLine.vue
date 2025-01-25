<script setup>
const props = defineProps({
    sentence: Object,
});
</script>

<template>
    <div class="dialog-line-wrapper" style="direction: rtl">
        <div class="dialog-line-container">
            <input v-model="sentence.speaker" class="dialog-line-head"/>
            <div class="dialog-line-body">
                <div class="dialog-arb">{{ sentence.sentence }}</div>
                <div class="dialog-eng">{{ sentence.trans }}</div>
            </div>
        </div>
    </div>
</template>
