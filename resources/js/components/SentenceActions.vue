<script setup>
import {route} from 'ziggy-js';
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmDelete = (event) => {
    if (!confirm(`Are you sure you want to delete this Sentence?`)) {
        event.preventDefault();
    }
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <a :href="route('sentences.show', model.id)">View Sentence</a>

            <template v-if="UserStore.isAdmin">
                <a :href="route('sentences.edit', model.id)">Edit Sentence</a>

                <form :action="route('sentences.destroy', model.id)" method="POST" @submit="confirmDelete">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Delete Sentence</button>
                </form>
            </template>
        </div>
    </div>
</template>
