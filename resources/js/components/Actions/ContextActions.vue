<script setup>
import {useActions} from "../../composables/Actions.js";

const props = defineProps({
    icon: {type: String, default: 'symbol'},
});

const {toggleMenu, onMenuKeydown, floatingStyles, isOpen, reference, floating, closeMenu} = useActions();

defineExpose({
    closeMenu
});
</script>

<template>
    <div class="popup-menu-wrapper">
        <img v-if="icon === 'emoji'" ref="reference" @click="toggleMenu()" class="gear"
             src="/img/gear.svg" alt="Options"
             role="button" tabindex="0" @keydown.enter.prevent="toggleMenu(true)"
             :aria-expanded="isOpen" aria-haspopup="menu" aria-controls="popup-menu"
        />
        <button v-if="icon === 'symbol'" ref="reference" @click="toggleMenu()" class="material-symbols-rounded"
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
