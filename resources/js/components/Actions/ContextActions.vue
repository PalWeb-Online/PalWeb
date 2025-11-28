<script setup>
import {useActions} from "../../composables/Actions.js";

const {toggleMenu, onMenuKeydown, floatingStyles, isOpen, reference, floating, closeMenu} = useActions();

defineExpose({
    closeMenu
});
</script>

<template>
    <div class="popup-menu-wrapper" :class="{ active: isOpen }">
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
