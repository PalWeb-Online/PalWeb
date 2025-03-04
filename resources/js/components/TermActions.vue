<script setup>
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3'
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const confirmDelete = (event) => {
    if (!confirm(`Are you sure you want to delete this Term?`)) {
        event.preventDefault();
    }
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <Link :href="route('terms.show', model.slug)">View Term</Link>

            <template v-if="UserStore.isAdmin">
                <Link :href="route('terms.edit', model.id)">Edit Term</Link>

                <form :action="route('terms.destroy', model.id)" method="POST" @submit="confirmDelete">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit">Delete Term</button>
                </form>
            </template>
        </div>
    </div>
</template>
