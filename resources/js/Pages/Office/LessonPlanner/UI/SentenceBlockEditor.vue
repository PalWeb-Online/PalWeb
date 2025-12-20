<script setup>
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {ref, watch} from "vue";
import SentenceItem from "../../../../components/SentenceItem.vue";
import SentenceBlock from "../../../Academy/Lessons/UI/SentenceBlock.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const SearchStore = useSearchStore();
const isSearchingForMe = ref(false);

const openSearch = () => {
    isSearchingForMe.value = true;
    SearchStore.openSearchGenie('insert', 'sentences');
};

const insertSentence = (model) => {
    props.block.model = {id: model.id};
    props.block.custom = null;
}

const addSentence = () => {
    props.block.model = null;
    props.block.custom = {
        transl: '',
        terms: []
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
    <div class="block--text">
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

        <div v-if="block.model || block.custom"
             style="display: flex; justify-content: space-between; align-items: center"
        >
            <button class="material-symbols-rounded" @click="clearSentence">mop</button>

            <SentenceItem v-if="block.model" :model="block.model"/>
            <SentenceBlock v-else-if="block.custom" :sentence="block.custom"/>
        </div>

        <div v-if="block.custom" style="display: grid; gap: 0.8rem">
            <input v-model="block.custom.transl" placeholder="Translation"/>
            <div v-for="(term, i) in block.custom.terms" style="display: flex">
                <input v-model="block.custom.terms[i].term" placeholder="Term"/>
                <input v-model="block.custom.terms[i].transc" placeholder="Transcription"/>
                <button class="material-symbols-rounded" @click="removeTerm(i)">delete</button>
            </div>
        </div>

    </div>
</template>
