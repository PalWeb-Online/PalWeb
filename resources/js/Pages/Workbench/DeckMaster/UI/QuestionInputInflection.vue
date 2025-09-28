<script setup>
import {computed} from "vue";

const props = defineProps({
    question: Object,
    index: Number,
    showTranslit: Boolean,
})

const prompt = computed(() => {
    switch (props.question.prompt) {
        case 'fem':
            return 'feminine';
        case 'plr':
            return 'plural';
        case 'elt':
            return 'elative';
        case 'pauc':
            return 'paucal';
        case 'sing':
            return 'singulative';
        case 'cnst':
            return 'construct';
        case 'resp':
            return 'response';
    }
})
</script>
<template>
    <div class="quiz-question">
        <div class="term-flashcard quiz-inflections flipped" style="cursor: default">
            <div class="term-flashcard-back">
                <div class="term-flashcard-head">
                    <div class="term-flashcard-headword">
                        <div>{{ question.term.term }}</div>
                        <div v-show="showTranslit">({{ question.term.translit }})</div>
                    </div>
                </div>
                <div class="term-flashcard-glosses">
                    <div class="term-flashcard-category">{{ prompt }}</div>
                    <input v-model="question.response"/>
                </div>
            </div>
        </div>
    </div>
</template>
