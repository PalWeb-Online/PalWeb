<script setup>
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3';
import {useUserStore} from "../stores/UserStore.js";
import {useActions} from "../composables/Actions.js";
import {useNotificationStore} from "../stores/NotificationStore.js";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    model: Object,
});

const UserStore = useUserStore();
const NotificationStore = useNotificationStore();

const deleteSentence = () => {
    if (!confirm('Are you sure you want to delete this Sentence?')) return;

    Inertia.delete(route('sentences.destroy', props.model.id), {
        onSuccess: () => {
            NotificationStore.addNotification('The Sentence has been deleted!');
        }
    });
};

const {toggleMenu, floatingStyles, isOpen, reference, floating} = useActions();
</script>

<template>
    <div class="popup-menu-wrapper">
        <img ref="reference" class="gear" src="/img/gear.svg" @click="toggleMenu" alt="options"/>

        <div ref="floating" v-if="isOpen" :style="floatingStyles" class="popup-menu">
            <Link :href="route('sentences.show', model.id)">View Sentence</Link>

            <template v-if="UserStore.isAdmin">
                <Link :href="route('speech-maker.sentence', model.id)">Edit Sentence</Link>
                <button @click="deleteSentence">Delete Sentence</button>
            </template>
        </div>
    </div>
</template>
