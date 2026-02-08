<script setup>
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {inject, ref, watch} from "vue";
import SentenceItem from "../../../../components/SentenceItem.vue";
import SentenceBlock from "../../../Academy/Lessons/UI/SentenceBlock.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const lessonSentences = inject('lessonSentences');

const SearchStore = useSearchStore();
const isSearchingForMe = ref(false);

const openSearch = () => {
    isSearchingForMe.value = true;
    SearchStore.openSearchGenie('insert', 'sentences');
};

const insertSentence = (model) => {
    lessonSentences.value[model.id] = model;

    props.block.model = {id: model.id};
    props.block.custom = null;
}

const addSentence = () => {
    props.block.model = null;
    props.block.custom = {
        transl: '',
        terms: [
            {term: '', transc: ''}
        ]
    }
}

const clearSentence = () => {
    props.block.model = null;
    props.block.custom = null;
}

const addTerm = () => {
    props.block.custom.terms.push({
        term: '',
        transc: '',
    });
}

const removeTerm = (index) => {
    props.block.custom.terms.splice(index, 1)
}

watch(
    () => SearchStore.data.selectedModel,
    (newModel) => {
        if (newModel && isSearchingForMe.value) {
            insertSentence(newModel);
            isSearchingForMe.value = false;
            SearchStore.deselectModel();
        }
    }
);
</script>

<template>
    <div class="block-editor--sentence">
        <div class="block-add-buttons">
            <template v-if="!block.model && !block.custom">
                <div>
                    <div class="add-button" @click="openSearch">+</div>
                    <div>model</div>
                </div>
                <div>
                    <div class="add-button" @click="addSentence">+</div>
                    <div>custom</div>
                </div>
            </template>
            <div v-else-if="block.custom">
                <div class="add-button" @click="addTerm">+</div>
                <div>term</div>
            </div>
        </div>

        <div v-if="block.model || block.custom" class="sentence-preview">
            <button class="material-symbols-rounded" @click="clearSentence">mop</button>
            <SentenceItem v-if="block.model" :model="lessonSentences[block.model.id]"/>
            <SentenceBlock v-else-if="block.custom" :sentence="block.custom"/>
        </div>

        <template v-if="block.custom">
            <div class="sentence-fields">
                <div v-for="(term, i) in block.custom.terms" class="sentence-term">
                    <input v-model="block.custom.terms[i].term" placeholder="Term"/>
                    <input v-model="block.custom.terms[i].transc" style="direction: ltr" placeholder="Transcription"/>
                    <button v-if="block.custom.terms.length > 1" class="material-symbols-rounded"
                            @click="removeTerm(i)">delete
                    </button>
                </div>
            </div>
            <div class="field-item">
                <input v-model="block.custom.transl" style="direction: ltr" placeholder="Translation"/>
            </div>
        </template>
    </div>
</template>

<style lang="scss" scoped>
.block-editor--sentence {
    display: grid;
    gap: 1.6rem;

    .sentence-preview {
        display: flex;
        align-items: center;
        gap: 1.6rem;
        direction: rtl;
    }

    .sentence-preview button,
    .sentence-term button {
        color: var(--color-medium-primary)
    }

    .sentence-fields {
        display: grid;
        gap: 0.8rem;
        direction: rtl;
    }

    .sentence-term {
        display: flex;
        gap: 0.8rem;
    }
}
</style>
