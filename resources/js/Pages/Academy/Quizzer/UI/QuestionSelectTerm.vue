<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import AppButton from "../../../../components/AppButton.vue";

const QuizzerStore = useQuizzerStore();

const props = defineProps({
    question: Object,
    index: Number,
    showTranslit: Boolean,
})

const toggleSelection = (index) => {
    if (QuizzerStore.quiz[props.index].response === Number(index)) {
        QuizzerStore.quiz[props.index].response = null;
    } else {
        QuizzerStore.quiz[props.index].response = Number(index);
    }
}
</script>
<template>
    <div class="quiz-question">
        <div class="sentence-item-wrapper l">
            <div class="sentence-item">
                <div class="sentence-arb">
                    <template v-if="question.sentence.terms.length > 0" v-for="term in question.sentence.terms">
                        <div v-if="term.sentencePivot.sent_term" class="sentence-term">
                            <div>{{ term.sentencePivot.sent_term }}</div>
                            <div v-if="showTranslit">{{ term.sentencePivot.sent_translit }}</div>
                        </div>
                        <div v-else class="sentence-term missing">
                            <div>{{ question.options[QuizzerStore.quiz[props.index].response]?.term ?? 'ــــــــ' }}</div>
                            <div v-if="showTranslit">{{ question.options[QuizzerStore.quiz[props.index].response]?.translit ?? '[]'}}</div>
                        </div>
                    </template>
                </div>

                <div class="sentence-eng" v-if="QuizzerStore.settings.options.withPrompt">
                    {{ question.sentence.trans }}
                </div>
            </div>
        </div>

        <div class="quiz-question-options">
            <AppButton v-for="(option, i) in question.options"
                       :class="{'selected': question.response === Number(i)}"
                       @click="toggleSelection(i)" :label="option.term"/>
        </div>
    </div>
</template>
