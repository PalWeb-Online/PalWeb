<script setup>
import {computed, onBeforeUnmount, ref} from 'vue';
import {flip, offset, shift, useFloating} from '@floating-ui/vue';
import TermActions from "./TermActions.vue";

const props = defineProps({
    modelType: String,
    triggerURL: String,

    // ModelActions
    routes: Object,
    isUser: Boolean,
    isAdmin: Boolean,

    // ActionButtons
    userDecks: Object,
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
        default:
            return null;
    }
});

const getFilteredProps = computed(() => {
    switch (props.modelType) {
        case 'term':
            return {
                routes: props.routes,
                isUser: props.isUser,
                isAdmin: props.isAdmin,

                // ActionButtons
                userDecks: props.userDecks,
            };
        default:
            return {};
    }
});

</script>

<template>
    <div class="context-actions-wrapper">
        <img ref="reference" :src="triggerURL" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="context-actions-menu">
            <component :is="getActionsComponent" v-bind="getFilteredProps"/>
        </div>
    </div>
</template>
