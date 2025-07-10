<script setup>
import {ref} from "vue";

defineProps({
    question: {type: String, default: ''},
    dismissable: {type: Boolean, default: false},
})

const isOpen = ref(true);
</script>

<template>
    <div class="app-tip" v-if="isOpen">
<!--        <button v-if="dismissable" class="material-symbols-rounded" @click="isOpen = false">close</button>-->
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
