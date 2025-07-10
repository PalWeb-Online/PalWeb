<script setup>
import {ref} from "vue";

const props = defineProps({
    visible: {type: Boolean, default: true},
});

const sectionVisible = ref(props.visible);
</script>
<template>
    <div class="window-section-head">
        <slot name="title"/>
        <button class="window-section-toggle material-symbols-rounded" @click="sectionVisible = !sectionVisible">
            {{ sectionVisible ? 'collapse_all' : 'expand_all' }}
        </button>
    </div>
    <section v-if="sectionVisible">
        <slot name="content"/>
    </section>
</template>
