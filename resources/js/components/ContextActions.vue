<script setup>
import {computed, onBeforeUnmount, ref} from 'vue';
import {flip, offset, shift, useFloating} from '@floating-ui/vue';
import TermActions from "./TermActions.vue";
import DeckActions from "./DeckActions.vue";
import SentenceActions from "./SentenceActions.vue";

const props = defineProps({
    imageURL: String,
    modelType: String,

    // ModelActions
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,

    // DeckActions
    isAuthor: Boolean,
});

const isOpen = ref(false);
const reference = ref(null);
const floating = ref(null);

const {floatingStyles} = useFloating(reference, floating, {
    placement: 'bottom',
    middleware: [offset(4), flip(), shift()],
});

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        document.addEventListener('click', handleClickOutside);
    } else {
        document.removeEventListener('click', handleClickOutside);
    }
};

const handleClickOutside = (event) => {
    if (!reference.value.contains(event.target) && !floating.value.contains(event.target)) {
        isOpen.value = false;
        document.removeEventListener('click', handleClickOutside);
    }
};

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const getActionsComponent = computed(() => {
    switch (props.modelType) {
        case 'term':
            return TermActions;
        case 'deck':
            return DeckActions;
        case 'sentence':
            return SentenceActions;
        default:
            return null;
    }
});

const getFilteredProps = computed(() => {
    const defaultProps = {
        modelType: props.modelType,
        routes: props.routes,
        isUser: props.isUser,
        isAdmin: props.isAdmin,
    };

    switch (props.modelType) {
        case 'deck':
            return {
                ...defaultProps,
                isAuthor: props.isAuthor,
            };
        default:
            return defaultProps;
    }
});

</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" :src="`${imageURL}/gear.svg`" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <component :is="getActionsComponent" v-bind="getFilteredProps"/>
        </div>
    </div>
</template>
