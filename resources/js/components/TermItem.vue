<script setup>
import {useTerm} from "../composables/terms/useTerm.js";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import TermActions from "./Actions/TermActions.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import CardItem from "./CardItem.vue";
import AudioButton from "./AudioButton.vue";
import {computed, ref} from "vue";

const UserStore = useUserStore();

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    glossId: {type: Number, default: null},
});

const {term, isLoading} = useTerm(props);

const currentGlossIndex = ref(0);

const shouldCycleGlosses = computed(() => {
    return !props.glossId && term?.glosses?.length > 1;
});

const visibleGloss = computed(() => {
    if (!term?.glosses?.length) {
        return '';
    }

    if (props.glossId) {
        return term.glosses.find((gloss) => gloss.id === props.glossId)?.gloss ?? term.glosses[0].gloss;
    }

    return term.glosses[currentGlossIndex.value]?.gloss ?? term.glosses[0].gloss;
});

const showPreviousGloss = () => {
    if (!shouldCycleGlosses.value) return;

    currentGlossIndex.value = currentGlossIndex.value === 0
        ? term.glosses.length - 1
        : currentGlossIndex.value - 1;
};

const showNextGloss = () => {
    if (!shouldCycleGlosses.value) return;

    currentGlossIndex.value = currentGlossIndex.value === term.glosses.length - 1
        ? 0
        : currentGlossIndex.value + 1;
};
</script>

<template>
    <template v-if="! isLoading">
        <div class="model-item-container term-item-container">
            <div class="model-item term-item">
                <PinButton modelType="term" :model="term"/>
                <div class="model-item-content">
                    <div class="term-item-gloss">
                        <div v-if="shouldCycleGlosses" class="term-item-gloss-controls">
                            <button
                                type="button"
                                class="material-symbols-rounded"
                                aria-label="Previous gloss"
                                @click="showPreviousGloss"
                            >
                                keyboard_arrow_up
                            </button>
                            <button
                                type="button"
                                class="material-symbols-rounded"
                                aria-label="Next gloss"
                                @click="showNextGloss"
                            >
                                keyboard_arrow_down
                            </button>
                        </div>
                        <div class="term-item-gloss-text">
                            {{ visibleGloss }}
                        </div>
                    </div>
                    <div class="term-item-term">
                        <Link style="height: 100%; overflow: scroll; display: flex; align-items: center; gap: 1.2rem;"
                              :href="route('terms.show', term.slug)">
                            <span class="arb">{{ term.term }}</span>
                            <span class="translit">({{ term.translit }})</span>
                        </Link>
                        <AudioButton v-if="term.audio" :pronunciation="term.pronunciations[0]"/>
                    </div>
                </div>
                <TermDeckToggleButton :model="term"/>
                <TermActions :model="term"/>
            </div>
            <CardItem v-if="UserStore.isStudent && model.card" :card="model.card"/>
        </div>
    </template>
</template>

<style scoped lang="scss">
.term-item-gloss-controls {
    display: grid;
    grid-template-rows: repeat(2, 1fr);
    width: 2.4rem;
    background: var(--color-pastel-medium);

    & > button {
        font-size: 1.6rem;
    }

    & > button:active {
        background: var(--color-pastel-dark);
    }
}
</style>
