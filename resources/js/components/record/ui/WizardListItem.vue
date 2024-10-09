<script setup>
import {computed} from 'vue';
import {useStore} from 'vuex'; // Or similar store management if you're using it

const props = defineProps({
    word: {type: String, required: true}
});

const store = useStore(); // Assume you're using Vuex or similar store pattern

// Access status and errors from rw.store.record.data
const status = computed(() => store.state.record.data.status);
const errors = computed(() => store.state.record.data.errors);

// Compute the item class based on the status and error
const itemClass = computed(() => {
    let classes = 'mwe-rws-' + status.value[props.word];
    if (errors.value[props.word] !== false) {
        classes += ' mwe-rw-error'; // Add error class if there's an error
    }
    return classes;
});

// Methods for handling events
const emit = defineEmits(['play', 'remove', 'click']);

const handlePlay = () => emit('play');
const handleRemove = () => emit('remove');
const handleClick = () => emit('click');
</script>

<template>
    <li :class="itemClass" @click="handleClick">
        <!-- Display word and errors as a tooltip -->
        <span v-html="word" :title="errors[word]"></span>

        <!-- Play icon (click to play) -->
        <i class="mwe-rws-play" @click.stop.prevent="handlePlay"></i>

        <!-- Remove icon (click to remove) -->
        <i class="mwe-rws-again" @click.stop.prevent="handleRemove"></i>
    </li>
</template>

<style scoped>
li {
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.mwe-rws-play, .mwe-rws-again {
    cursor: pointer;
    margin-left: 10px;
}

.mwe-rw-error {
    color: red;
}
</style>
