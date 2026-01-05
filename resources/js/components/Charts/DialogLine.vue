<script setup>
defineProps({
    align: {type: String, default: 'rtl'},
    speaker: String,
    ar: 'arb',
    en: 'eng',
});
</script>
<template>
    <div class="dialog-line-wrapper" :class="{ reverse: align === 'ltr' }">
        <div v-if="speaker" class="dialog-line-head">
            {{ speaker }}
        </div>
        <div class="dialog-line-body">
            <div class="dialog-arb">
                {{ ar }}
            </div>
            <div v-if="en" class="dialog-eng">
                {{ en }}
            </div>
        </div>
    </div>
</template>
