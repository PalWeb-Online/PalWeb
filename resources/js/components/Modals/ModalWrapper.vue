<script setup>
import {computed, onBeforeUnmount, onMounted, watch} from "vue";
import {useNavigationStore} from "../../stores/NavigationStore.js";

const NavigationStore = useNavigationStore();

const props = defineProps({
    modelValue: {type: Boolean, default: false},
});

const emit = defineEmits(['update:modelValue', 'close']);

const modalOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

onMounted(() => {
    const globalListener = (event) => {
        if (event.key === 'Escape' && modalOpen.value === true) {
            event.preventDefault();
            modalOpen.value = false;
        }
    };

    window.addEventListener('keydown', globalListener);

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', globalListener);
    });
});

watch(modalOpen, (newValue) => {
    if (newValue) {
        NavigationStore.closeSidebar();
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

function closeModal() {
    modalOpen.value = false;
    emit('close');
}
</script>

<template>
    <teleport to="body">
        <transition name="modal-slide">
            <div
                v-if="modalOpen"
                class="modal-viewbox"
                @click.self="closeModal"
            >
                <slot/>
            </div>
        </transition>
    </teleport>
</template>
