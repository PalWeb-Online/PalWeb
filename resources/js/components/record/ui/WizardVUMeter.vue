<script setup>
import {toRefs} from 'vue';

const props = defineProps({
    value: {
        type: Number,
        default: 0,
    },
    saturated: {
        type: Boolean,
        default: false,
    },
});

const {value} = toRefs(props);

const getClass = (index) => {
    return value.value > index ? 'active' : '';
};
</script>

<template>
    <ul>
        <li :class="getClass(6)"></li>
        <li :class="getClass(7)"></li>
        <li :class="getClass(8)"></li>
        <li :class="getClass(9)"></li>
        <li :class="getClass(10)"></li>
        <li :class="getClass(11)"></li>
        <li :class="getClass(12)"></li>
        <li :class="getClass(13)"></li>
        <li :class="getClass(14)"></li>
        <li :class="getClass(15)"></li>
        <li :class="getClass(16)"></li>
        <li :class="getClass(17)"></li>
        <li :class="getClass(18)"></li>
        <li :class="getClass(19)"></li>
        <li :class="getClass(20)"></li>
        <li :class="getClass(21)"></li>
        <li :class="getClass(22)"></li>
        <li :class="getClass(23)"></li>
        <li :class="getClass(24)"></li>
        <li :class="getClass(25)"></li>
    </ul>
</template>
