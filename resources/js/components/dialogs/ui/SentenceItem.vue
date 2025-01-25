<script setup>
const props = defineProps({
    sentence: Object,
});
</script>

<template>
    <div class="sentence-wrapper">
        <div class="sentence-item l">
            <div class="sentence-arb">
                <template v-for="term in sentence.terms">
                    <div class="sentence-term">
                        <div>{{ term.pivot.sent_term }}</div>
                        <div>{{ term.pivot.sent_translit }}</div>
                    </div>
                </template>
            </div>
            <input class="sentence-eng" v-model="sentence.trans"/>
        </div>
    </div>
</template>
