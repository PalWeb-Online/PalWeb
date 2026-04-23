<script setup>
import {autoUpdate, flip, offset, shift, useFloating} from "@floating-ui/vue";
import {computed, ref} from "vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    message: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const isVisible = computed(() => props.modelValue);
const reference = ref(null);
const floating = ref(null);

const {floatingStyles} = useFloating(reference, floating, {
    placement: 'bottom',
    whileElementsMounted: autoUpdate,
    middleware: [
        offset(12),
        flip({
            padding: 8,
        }),
        shift({
            padding: 8,
        }),
    ],
});

const setReference = (el) => {
    reference.value = el;
};

const dismiss = () => {
    emit('update:modelValue', false);
};
</script>

<template>
    <div class="app-hint-wrapper">
        <slot :set-reference="setReference" />

        <Teleport to="body">
            <div
                v-if="isVisible"
                ref="floating"
                :style="floatingStyles"
                class="app-tooltip app-hint"
                role="dialog"
                aria-live="polite"
            >
                <div class="app-hint__text">
                    {{ message }}
                </div>

                <button
                    type="button"
                    class="app-hint__dismiss"
                    @click="dismiss"
                >
                    Dismiss
                </button>
            </div>
        </Teleport>
    </div>
</template>
