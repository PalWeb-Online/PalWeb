<script setup>
import {route} from "ziggy-js";
import PinButton from "../../../../components/PinButton.vue";
import SentenceActions from "../../../../components/SentenceActions.vue";

const props = defineProps({
    sentence: Object,
    page: String,
    inDialog: {type: Boolean, default: false},
});
</script>

<template>
    <div :class="['sentence-item-wrapper', {
        m: page === 'dialog',
        l: page === 'sentence',
    }]">
        <PinButton v-if="sentence.id" modelType="sentence" :model="sentence"/>
        <SentenceActions v-if="sentence.id" :model="sentence"/>
        <div class="sentence-item">
            <template v-if="inDialog">
                <input v-model="sentence.speaker" class="sentence-speaker"/>
            </template>

            <div class="sentence-arb" style="user-select: none">
                <template v-if="sentence.terms.length > 0">
                    <template v-for="term in sentence.terms">
                        <div class="sentence-term">
                            <div>{{ term.sentencePivot.sent_term }}</div>
                            <div>{{ term.sentencePivot.sent_translit }}</div>
                        </div>
                    </template>
                </template>
                <template v-else>
                    <div class="sentence-term" style="background: none">
                        <div>{{ sentence.sentence }}</div>
                    </div>
                </template>
            </div>

            <template v-if="page === 'dialog'">
                <div class="sentence-eng">{{ sentence.trans }}</div>
            </template>
            <template v-else>
                <input class="sentence-eng" v-model="sentence.trans"/>
            </template>
        </div>

        <div v-if="page === 'sentence' && sentence.dialog?.id" class="sentence-dialog">
            <div>Dialog</div>
            <Link :href="route('speech-maker.dialog', sentence.dialog.id)">{{ sentence.dialog.title }}</Link>
        </div>
    </div>
</template>
