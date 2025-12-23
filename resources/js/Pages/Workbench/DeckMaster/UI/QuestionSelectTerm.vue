<script setup>
import {useDeckStudyStore} from "../Stores/DeckStudyStore.js";
import AppButton from "../../../../components/AppButton.vue";

const DeckStudyStore = useDeckStudyStore();

const props = defineProps({
    question: Object,
    index: Number,
    showTranslit: Boolean,
})

const toggleSelection = (index) => {
    if (DeckStudyStore.quiz[props.index].response === Number(index)) {
        DeckStudyStore.quiz[props.index].response = null;
    } else {
        DeckStudyStore.quiz[props.index].response = Number(index);
    }
}
</script>
<template>
    <div class="quiz-question">
        <div class="model-item-container sentence-item-container">
            <div class="model-item sentence-item">
                <div class="model-item-content">
                    <template v-if="question.sentence.terms.length > 0" v-for="term in question.sentence.terms">
                        <div v-if="term.sentencePivot.sent_term" class="sentence-term">
                            <div>{{ term.sentencePivot.sent_term }}</div>
                            <div v-if="showTranslit">{{ term.sentencePivot.sent_translit }}</div>
                        </div>
                        <div v-else class="sentence-term missing">
                            <div>{{ question.options[DeckStudyStore.quiz[props.index].response]?.term ?? 'ــــــــ' }}</div>
                            <div v-if="showTranslit">{{ question.options[DeckStudyStore.quiz[props.index].response]?.translit ?? '[]'}}</div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="model-item-description" v-if="DeckStudyStore.settings.options.withTranslation">
                {{ question.sentence.trans }}
            </div>
        </div>

        <div class="exercise--select-choices">
            <AppButton v-for="(option, i) in question.options"
                       :class="{'selected': question.response === Number(i)}"
                       @click="toggleSelection(i)" :label="option.term"/>
        </div>
    </div>
</template>
