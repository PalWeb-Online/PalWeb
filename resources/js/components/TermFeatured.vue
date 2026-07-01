<script setup>
import {useTerm} from "../composables/terms/useTerm.js";
import TermActions from "./Actions/TermActions.vue";
import PinButton from "./PinButton.vue";
import GlossItem from "./GlossItem.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const {term, isLoading} = useTerm(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="window-section-head">
            <h2>featured</h2>
            <PinButton modelType="term" :model="term"/>
            <TermActions :model="term"/>
        </div>
        <section class="featured-term">
            <img alt="Term Image" :src="term.image">
            <div>
                <div class="term-container-head">
                    <div class="term-headword">
                        <div class="term-headword-term">
                            <div class="term-headword-arb">{{ term.term }}</div>
                            <div class="term-headword-eng">({{ term.translit }})</div>
                        </div>

                        <div class="term-headword-data">{{ term.category }}.
                            <template v-for="attribute in term.attributes" :key="attribute.id">
                                <template v-if="['idiom', 'clitic'].includes(attribute.attribute)">
                                <span style="font-weight: 400; font-style: italic">
                                    {{ attribute.attribute }}.
                                </span>
                                </template>
                                <template v-else>
                                    <span style="font-weight: 400">{{ attribute.attribute }}.</span>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="term-glosses">
                    <GlossItem v-for="(gloss, index) in term.glosses" :key="gloss.id" :gloss="gloss" :position="index + 1"/>
                </div>
            </div>
        </section>
    </template>
</template>

<style scoped lang="scss">
.featured-term {
    display: grid;
    overflow: hidden;
    position: relative;

    img {
        min-height: 100%;
        object-fit: cover;
    }

    @media (width >= 960px) {
        grid-template-columns: 1fr 2fr;
    }
}
</style>
