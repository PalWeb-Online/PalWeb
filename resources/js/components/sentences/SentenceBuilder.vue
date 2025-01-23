<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import {cloneDeep} from 'lodash';
import draggable from 'vuedraggable';
import SentenceItem from "./ui/SentenceItem.vue";
import TermItem from "./ui/TermItem.vue";
import SearchGenie from "../search/SearchGenie.vue";
import AppNotification from "../AppNotification.vue";
import AppButton from "../AppButton.vue";

const notification = ref(null);

const data = reactive({
    action: window.action,
    errorMessage: null,
    stagedSentence: {
        id: null,
        sentence: '',
        translit: '',
        trans: '',
        terms: [],
    },
    originalSentence: null,
});

onMounted(() => {
    if (data.action === 'edit') {
        data.stagedSentence = window.stagedSentence;
    }

    data.originalSentence = cloneDeep(data.stagedSentence);
})


const hasUnsavedChanges = computed(() => {
    return JSON.stringify(data.originalSentence) !== JSON.stringify(data.stagedSentence);
});

const isValidRequest = computed(() => {
    if (!data.stagedSentence.trans) return false;

    if (data.stagedSentence.terms.length === 0) return false;

    for (const term of data.stagedSentence.terms) {
        if (!term.pivot.sent_term || !term.pivot.sent_translit) {
            return false;
        }
    }

    return true;
});

const exit = async () => {
    if (hasUnsavedChanges.value && !confirm('Are you sure you would like to exit? All your unsaved changes will be lost.')) return;
    window.location.href = '/workbench';
};

const updatePosition = () => {
    data.stagedSentence.terms.forEach((term, index) => {
        term.pivot.position = index + 1;
    });
}

const addTerm = () => {
    data.stagedSentence.terms.push({
        pivot: {
            sent_term: '',
            sent_translit: '',
            position: '',
        }
    });
    updatePosition();
}

const removeTerm = (index) => {
    data.stagedSentence.terms.splice(index, 1);
    updatePosition();
}

const insertTerm = (term) => {
    data.stagedSentence.terms.push({
        id: term.id,
        term: term.term,
        category: term.category,
        translit: term.translit,
        glosses: term.glosses.map((gloss) => ({
            id: gloss.id,
            gloss: gloss.gloss,
        })),
        pivot: {
            gloss_id: term.glosses[0].id,
            sent_term: term.term,
            sent_translit: term.translit,
            position: '',
        }
    });
    updatePosition();
    notification.value.showNotification(`Added ${term.term} to the Sentence!`);
}

const saveSentence = async () => {
    try {
        if (data.action === 'create') {
            const response = await axios.post('/dictionary/sentences', {
                sentence: data.stagedSentence,
            });

            data.stagedSentence.id = response.data.sentence.id;

        } else {
            await axios.patch('/dictionary/sentences/' + data.stagedSentence.id, {
                sentence: data.stagedSentence,
            });
        }

        data.errorMessage = null;
        data.originalSentence = cloneDeep(data.stagedSentence);
        notification.value.showNotification('The Sentence has been saved!');

    } catch (error) {
        data.errorMessage = error.response?.data?.message || 'Oh no! The Sentence could not be saved.';
        notification.value.showNotification(data.errorMessage, 'error');
    }
};

const resetSentence = async () => {
    data.stagedSentence = cloneDeep(data.originalSentence);
};

const viewSentence = async () => {
    window.open(`/dictionary/sentences/${data.stagedSentence.id}`, '_blank');
};

const deleteSentence = async () => {
    try {
        await axios.delete(`/dictionary/sentences/${data.stagedSentence.id}`);

        data.errorMessage = null;
        notification.value.showNotification('The Sentence has been deleted!');

        setTimeout(() => {
            window.location.href = '/dictionary/sentences';
        }, 1000);

    } catch (error) {
        data.errorMessage = error.response?.data?.message || 'Oh no! The Sentence could not be deleted.';
        notification.value.showNotification(data.errorMessage, 'error');
    }
};
</script>

<template>
    <div id="app-head">
        <button @click="exit">Exit to Workbench</button>
        <h1>Sentence Builder</h1>
    </div>

    <div id="app-body">
        <div class="db-container" style="max-width: 96rem">
            <div id="db-page-build" style="justify-items: normal">
                <SearchGenie :context="'builder'" @emitTerm="insertTerm($event)"/>

                <div class="db-build-buttons">
                    <AppButton :disabled="!hasUnsavedChanges || !isValidRequest" label="Save"
                               @click="saveSentence"
                    />
                    <AppButton :disabled="!hasUnsavedChanges" label="Reset"
                               @click="resetSentence"
                    />
                    <AppButton :disabled="!data.stagedSentence.id" label="View"
                               @click="viewSentence"
                    />
                    <AppButton :disabled="!data.stagedSentence.id" label="Delete"
                               @click="deleteSentence"
                    />
                </div>

                <SentenceItem :sentence="data.stagedSentence"/>

                <draggable :list="data.stagedSentence.terms" itemKey="id"
                           @end="updatePosition()"
                           class="draggable">
                    <template #item="{ element, index }">
                        <div class="db-item">
                            <TermItem :term="element"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="data.stagedSentence.terms.length > 0"
                                 @click="removeTerm(index)"/>
                        </div>
                    </template>
                </draggable>

                <div class="db-build-buttons">
                    <AppButton label="Add Term" @click="addTerm"/>
                </div>

                <AppNotification ref="notification"/>
            </div>
        </div>
    </div>
</template>

<!--<div class="sentence-preview" style="direction: rtl">-->
<!--<div v-if="data.terms.length < 1" class="sentence-preview-info">Sentence Preview</div>-->
<!--<template v-for="term in data.terms">-->
<!--    <div class="sentence-preview-term">-->
<!--        <div>{{ term.sent_term }}</div>-->
<!--        <div>{{ term.sent_translit }}</div>-->
<!--    </div>-->
<!--</template>-->
<!--</div>-->

<!--                            <div class="sentence-builder-item">-->
<!--                                <div class="db-item-term">-->
<!--                                    <input :id="'terms['+index+'][sent_term]'" :name="'terms['+index+'][sent_term]'"-->
<!--                                           type="text" v-model="element.sent_term"/>-->
<!--                                    <input :id="'terms['+index+'][sent_term]'" :name="'terms['+index+'][sent_term]'"-->
<!--                                           type="text" v-model="element.sent_translit"/>-->
<!--                                </div>-->

<!--                                <div class="db-item-gloss">-->
<!--                                    <select v-if="element.glosses.length" v-model="element.gloss_id">-->
<!--                                        <option v-for="gloss in element.glosses" :value="gloss.id">-->
<!--                                            {{-->
<!--                                                gloss.gloss.length > 60 ? gloss.gloss.slice(0, 60) + "..." : gloss.gloss-->
<!--                                            }}-->
<!--                                        </option>-->
<!--                                    </select>-->
<!-- </div>-->

<!--                                <input style="display: none" :id="'terms['+index+'][term_id]'"-->
<!--                                       :name="'terms['+index+'][term_id]'"-->
<!--                                       type="text" v-model="element.term_id"/>-->

<!--                            </div>-->
