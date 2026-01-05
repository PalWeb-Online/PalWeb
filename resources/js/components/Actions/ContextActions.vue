<script setup>
import {useActions} from "../../composables/Actions.js";
import {useSlots, computed, Comment, Text, Fragment} from "vue";

const {toggleMenu, onMenuKeydown, floatingStyles, isOpen, reference, floating, closeMenu} = useActions();

const slots = useSlots();

const hasContent = computed(() => {
    if (!slots.default) return false;

    return slots.default({ closeMenu: () => {} }).some(vnode => {
        if (vnode.type === Comment) return false;
        if (vnode.type === Text && !vnode.children.trim()) return false;
        if (vnode.type === Fragment && Array.isArray(vnode.children)) return vnode.children.length > 0;
        return true;
    });
});

defineExpose({
    closeMenu
});
</script>

<template>
    <div v-if="hasContent" class="popup-menu-wrapper" :class="{ active: isOpen }">
        <button ref="reference" @click="toggleMenu()" class="material-symbols-rounded"
                @keydown.enter.prevent="toggleMenu(true)">
            settings
        </button>

        <Teleport to="body">
            <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu"
                 role="menu" @keydown="onMenuKeydown"
            >
                <slot :closeMenu="closeMenu"></slot>
            </div>
        </Teleport>
    </div>
</template>
