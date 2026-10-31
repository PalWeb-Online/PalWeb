<script setup>
import {useDeckStudySession} from "../../../../composables/decks/useDeckStudySession.js";

const DeckStudySession = useDeckStudySession();

const props = defineProps({
    question: Object,
    index: Number,
    showTranslit: Boolean,
})

const toggleSelection = (index) => {
    if (DeckStudySession.quiz[props.index].response === Number(index)) {
        DeckStudySession.quiz[props.index].response = null;
    } else {
        DeckStudySession.quiz[props.index].response = Number(index);
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
                            <div>{{ question.options[DeckStudySession.quiz[props.index].response]?.term ?? 'ــــــــ' }}</div>
                            <div v-if="showTranslit">{{ question.options[DeckStudySession.quiz[props.index].response]?.translit ?? '[]'}}</div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="model-item-description" v-if="DeckStudySession.settings.options.withTranslation">
                {{ question.sentence.trans }}
            </div>
        </div>

        <div class="exercise--select-options">
            <button v-for="(option, i) in question.options"
                       :class="{'selected': question.response === Number(i)}"
                       @click="toggleSelection(i)">
                {{ option.term }}
            </button>
        </div>
    </div>
</template>
