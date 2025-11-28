<script setup>
import {useTerm} from "../composables/Term.js";
import TermActions from "./Actions/TermActions.vue";
import PinButton from "./PinButton.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    latestTerms: Object,
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
                    <div v-for="(gloss, index) in term.glosses" class="gloss-li-container">
                        <div class="gloss-li">
                            <div class="gloss-li-label">
                                {{ index + 1 }}
                            </div>

                            <div class="gloss-li-content">
                                <div v-for="attribute in gloss.attributes" class="gloss-li-attribute">
                                    <template v-if="attribute.category">[{{ attribute.category }}]</template>
                                    {{ attribute.attribute }}
                                </div>
                                <div class="gloss-li-content-gloss">
                                    {{ gloss.gloss }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--            <div class="term-item">-->
                <!--                <div class="term-item-head">-->
                <!--                    <div class="arb">{{ term.term }}</div>-->
                <!--                    <img class="play" v-if="term.audio" src="/img/audio.svg" alt="play" @click="playAudio"/>-->
                <!--                    <div class="translit">{{ term.translit }}</div>-->
                <!--                </div>-->
                <!--                <div class="term-item-body">-->
                <!--                    <div class="eng">{{ glossId ? term.glosses.find((gloss) => gloss.id === props.glossId).gloss : term.glosses[0].gloss }}</div>-->
                <!--                    <TermDeckToggleButton :model="term"/>-->
                <!--                </div>-->
                <!--                <PinButton modelType="term" :model="term"/>-->
                <!--            </div>-->
                <!--            <TermActions :model="term"/>-->
            </div>
        </section>
    </template>
</template>
