<script setup>
defineProps({
    align: 'rtl',
    speaker: String,
    ar: 'arb',
    en: 'eng',
});
</script>
<template>
    <div class="dialog-line-wrapper" :style="{ direction: align }">
        <div class="dialog-line-container">
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
    </div>
</template>
