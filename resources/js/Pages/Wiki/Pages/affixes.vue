<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
</template>

<!--<x-page-head title="{{ __('affixes') }}"-->
<!--             blurb="Affixes are bound morphemes used to build the inflections of other terms.">-->
<!--    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>-->
<!--    <a>{{ __('morphology') }}</a>-->
<!--    <x-link :href="route('wiki.show', 'affixes')">{{ __('affixes') }}</x-link>-->
<!--</x-page-head>-->

<!--<x-tip>-->
<!--    <p><b>Affixes</b> are not lemmas, nor a grammatical category per se, but they are included in the Dictionary for the-->
<!--        sake of convenience, as they are meaningful morphemes.</p>-->
<!--</x-tip>-->

<!--<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(32)"/>-->
