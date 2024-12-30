<script setup>
import {Howl} from 'howler';
import {computed, onMounted, ref} from 'vue';

const props = defineProps({
    term: Object,
});

const gloss = computed(() => {
    return props.term.gloss.length > 48
        ? props.term.gloss.substring(0, 45) + '...'
        : props.term.gloss;
});

</script>

<template>
    <div class="term-item-wrapper">
        <div class="term-item">
            <div class="term-item-head">
                <div class="arb">{{ term.term }}</div>
                <div class="translit">{{ term.translit }}</div>
            </div>
            <div class="term-item-body">
                <select class="eng" v-model="term.gloss_id">
                    <option v-for="gloss in term.glosses" :value="gloss.id">
                        {{ gloss.gloss.length > 60 ? gloss.gloss.slice(0, 60) + "..." : gloss.gloss }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>
