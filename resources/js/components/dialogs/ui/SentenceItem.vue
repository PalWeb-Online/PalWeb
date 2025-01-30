<script setup>
import {route} from "ziggy-js";

const props = defineProps({
    sentence: Object,
});
</script>

<template>
    <div :class="['sentence-item-wrapper', size]" style="grid-template-columns: 1fr">
        <div class="sentence-item">
            <div v-if="sentence.terms.length > 0" class="sentence-arb">
                <template v-for="term in sentence.terms">
                    <div class="sentence-term">
                        <div>{{ term.sentencePivot.sent_term }}</div>
                        <div>{{ term.sentencePivot.sent_translit }}</div>
                    </div>
                </template>
            </div>
            <input class="sentence-eng" v-model="sentence.trans"/>
        </div>
        <div v-if="sentence.dialog" class="sentence-dialog" style="grid-column: 1">
            <div>Dialog</div>
            <a :href="route('dialogs.show', sentence.dialog.id )" target="_blank">
                {{ sentence.dialog.title }}
            </a>
        </div>
    </div>
</template>
