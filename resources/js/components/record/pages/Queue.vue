<script setup>
import {onMounted, ref} from 'vue';
import {useRecordStore} from '../store/RecordStore';
import Draggable from 'vuedraggable';
import WizardButton from "../ui/WizardButton.vue";

const RecordStore = useRecordStore();

const pronunciations = ref(RecordStore.data.pronunciations);

const remove = (index) => {
    RecordStore.removePronunciation(pronunciations.value[index]);
};

const fetchMore = async () => {
    await RecordStore.fetchPronunciations(pronunciations.value);
    pronunciations.value = RecordStore.data.pronunciations;
};

onMounted(async () => {
    await RecordStore.fetchPronunciations();
    pronunciations.value = RecordStore.data.pronunciations;
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Queue</h2>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Because you selected <b>{{ RecordStore.data.metadata.speaker.dialect_id }}</b> as your dialect, the first
                100 Pronunciations in that dialect that are to be recorded have been automatically loaded in below. You
                may manually reorder the list or remove any Pronunciations that you do not wish to record. Once you are
                satisfied with this list, proceed to the next step to record.</p>
        </div>
    </div>

    <div class="wizard-section-container">
        <section>
            <draggable :list="pronunciations" id="wizard-queue">
                <template #item="{ element, index }">
                    <li class="wizard-queue-item">
                        <div>
                            {{ index+1 }}. <span>{{ element.term }}</span> ({{ element.translit }})
                        </div>
                        <img width="16"
                             class="mwe-rws-again" src="/img/trash.svg" alt="Delete" v-show="pronunciations.length > 0"
                             @click="remove(index)"
                        />
                    </li>
                </template>
            </draggable>

            <div class="wizard-queue-count">{{ pronunciations.length }} of 100</div>

<!--            disable if there are already 100 terms -->
            <WizardButton id="mwe-rwt-reopenpopup"
                          label="Add More"
                          :disabled="RecordStore.data.pronunciations.length >= 100"
                          @click="fetchMore"/>

        </section>
    </div>
</template>
