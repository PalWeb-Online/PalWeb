<script setup>
import {route} from 'ziggy-js';
import {useActions} from "../composables/Actions.js";

const props = defineProps({
    model: Object,
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmDelete = (event) => {
    if (!confirm(`Are you sure you want to delete this Dialog?`)) {
        event.preventDefault();
    }
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <a :href="route('dialogs.show', model.id)">View Dialog</a>
            <a :href="route('dialogs.edit', model.id)">Edit Dialog</a>
            <form :action="route('dialogs.destroy', model.id)" method="POST" @submit="confirmDelete">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit">Delete Dialog</button>
            </form>
        </div>
    </div>
</template>
