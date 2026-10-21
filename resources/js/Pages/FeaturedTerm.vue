<script setup>
import {onMounted, ref} from 'vue';
import {route} from 'ziggy-js';
import ZatuneNeutral from '../Shared/ZatuneNeutral.vue';
import ZatuneComment from '../components/ZatuneComment.vue';
import GlossItem from '../components/GlossItem.vue';
import {useTerm} from '../composables/terms/useTerm.js';

const term = ref(null);

const {
    term: featuredTerm,
    inflections,
    isLoading,
} = useTerm(term);

onMounted(async () => {
    const { data } = await axios.get(route('api.terms.featured'));
    term.value = data.term;
    term.value.glosses.forEach(gloss => gloss.attributes = []);
});
</script>

<template>
    <div v-if="! isLoading" class="featured-term-container">
        <div class="featured-term-wrapper">
            <div class="ft-headword">
                <div class="ft-headword-arb">{{ featuredTerm.term }}</div>
                <div class="ft-headword-eng">{{ featuredTerm.translit }}</div>
                <div v-if="inflections.length" class="ft-headword-inflections">
                    <span v-for="inflection in inflections">{{ inflection.inflection }}</span>
                </div>
                <div class="ft-headword-data">
                    <span style="font-weight: 700">
                        {{ featuredTerm.category }}.
                        {{ ' ' }}
                    </span>
                    <template v-for="attribute in featuredTerm.attributes" :key="attribute.id">
                        <template v-if="['idiom', 'clitic'].includes(attribute.attribute)">
                            <span style="font-style: italic">
                                {{ attribute.attribute }}.
                                {{ ' ' }}
                            </span>
                        </template>
                        <template v-else>
                            <span>
                                {{ attribute.attribute }}.
                                {{ ' ' }}
                            </span>
                        </template>
                    </template>
                </div>
            </div>

            <div class="term-gloss-wrapper" v-for="(gloss, index) in featuredTerm.glosses" :key="gloss.id">
                <GlossItem :gloss="gloss" :position="index + 1"/>

                <div class="tf-sentence" v-for="sentence in gloss.sentences" :key="sentence.id">
                    <div class="sentence">{{ sentence.sentence }}</div>
                    <div class="translation">{{ sentence.trans }}</div>
                </div>
            </div>

            <ZatuneComment v-if="featuredTerm.usage" :comment="featuredTerm.usage"/>

            <div v-if="featuredTerm.image" style="display: grid; place-items: center">
                <div class="zatune-image">
                    <ZatuneNeutral/>
                    <img :src="featuredTerm.image" alt="term image"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.featured-term-container {
    display: grid;
    padding-block: 6.4rem;
    place-items: center;
}

.featured-term-wrapper :deep {
    width: min(100%, 64rem);
    display: grid;
    grid-auto-rows: 64rem;
    gap: 3.2rem;
    align-content: start;

    & > * {
        outline: 0.8rem solid var(--color-accent-medium);
        padding: 6rem;
    }

    .term-gloss-wrapper {
        display: grid;
        align-content: center;
        gap: 3.2rem;

        .gloss-item-container {
            border: none;

            .gloss-item {
                gap: 3.2rem;
                padding: 0;
                grid-template-columns: min-content 1fr;
            }

            .gloss-item-content {
                padding-inline: 0;
                font-weight: 700;
                //font-family: var(--head-font);
                color: var(--color-dark-secondary);
            }
        }
    }
}

.ft-headword {
    display: flex;
    flex-flow: column;
    align-items: center;
    justify-content: center;
    gap: 4.8rem;
    color: var(--color-dark-secondary);

    & > * {
        text-box: trim-both cap alphabetic;
    }

    .ft-headword-arb {
        font-size: 9.6rem;
        font-weight: 400;
        font-family: var(--display-font);
    }

    .ft-headword-eng {
        font-size: 2.4rem;
        font-weight: 700;
        font-family: var(--mono-font);
    }

    .ft-headword-inflections {
        direction: rtl;
        display: flex;
        gap: 1.6rem;
        font-size: 3.2rem;
        font-weight: 400;
        font-family: var(--display-font);
    }

    .ft-headword-data {
        font-size: 2.0rem;
    }
}

.tf-sentence {
    display: grid;
    justify-items: end;
    color: var(--color-dark-secondary);
    background: hsl(from var(--color-accent-medium) h s l / 50%);
    gap: 2.4rem;
    padding: 2.4rem;
    border-radius: 1.6rem;

    .sentence {
        direction: rtl;
        font-family: var(--ar-body-font);
        font-weight: 700;
        font-size: 2.4rem;
        line-height: 1.25;
        text-box: trim-both cap alphabetic;
    }

    .translation {
        font-family: var(--body-font);
        font-size: 1.6rem;
        line-height: 1.5;
        font-weight: 700;
        text-box: trim-both cap alphabetic;
    }
}

.zatune-comment {
    padding: 0;
}

.zatune-image {
    width: 100%;
    display: grid;
    gap: 1.6rem;
    grid-template-columns: 25% 1fr;

    svg {
        align-self: end;
    }

    img {
        width: 100%;
        height: auto;
        padding: 0.4rem;
        background: var(--color-accent-light);
        border-radius: 1.6rem;
        border: 0.2rem solid var(--color-accent-medium);
        margin-block-end: 6.4rem;
    }
}
</style>
