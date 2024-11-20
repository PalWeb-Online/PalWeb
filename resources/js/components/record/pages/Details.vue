<script setup>
import {onMounted, ref, watch} from 'vue';
import useRecordStore from '../store/useRecordStore';
import Draggable from 'vuedraggable';
import WizardButton from "../ui/WizardButton.vue";

const recordStore = useRecordStore();

const pronunciations = ref(recordStore.data.pronunciations);

const remove = (index) => {
    recordStore.removePronunciation(pronunciations.value[index]);
};

const fetchMore = async () => {
    await recordStore.fetchPronunciations(pronunciations.value);
    pronunciations.value = recordStore.data.pronunciations;
};

onMounted(async () => {
    await recordStore.fetchPronunciations();
    pronunciations.value = recordStore.data.pronunciations;
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Details</h2>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Because you selected <b>{{ recordStore.data.metadata.speaker.dialect_id }}</b> as your dialect, the first
                100 Pronunciations in that dialect that are to be recorded have been automatically loaded in below. You
                may manually reorder the list or remove any Pronunciations that you do not wish to record. Once you are
                satisfied with this list, proceed to the next step to record.</p>
        </div>
    </div>

    <div class="wizard-section-container">
        <section>
            <div>Count: {{ pronunciations.length }}/100</div>

            <!-- Draggable List -->
            <draggable :list="pronunciations" id="mwe-rwd-list">
                <template #item="{ element, index }">
                    <li class="draggable-item">
                        <span>{{ index+1 }} {{ element.translit }} {{ element.id }}</span>
                        <img width="16"
                            class="mwe-rws-again" src="/img/trash.svg" alt="Delete" v-show="pronunciations.length > 0"
                             @click="remove(index)"
                        />
                    </li>
                </template>
            </draggable>

            <WizardButton id="mwe-rwt-reopenpopup" label="Add More"
                          @click="fetchMore"/>

        </section>
    </div>
</template>
