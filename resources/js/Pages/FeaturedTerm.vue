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
            <div class="term-headword">
                <div class="term-headword-term">
                    <div class="term-headword-arb">{{ featuredTerm.term }}</div>
                    <div class="term-headword-eng">({{ featuredTerm.translit }})</div>
                </div>
                <div class="term-headword-data">{{ featuredTerm.category }}.
                    <template v-for="attribute in featuredTerm.attributes" :key="attribute.id">
                        <template v-if="['idiom', 'clitic'].includes(attribute.attribute)">
                            <span style="font-weight: 400; font-style: italic">
                                {{ attribute.attribute }}.
                                {{ ' ' }}
                            </span>
                        </template>
                        <template v-else>
                            <span style="font-weight: 400">
                                {{ attribute.attribute }}.
                                {{ ' ' }}
                            </span>
                        </template>
                    </template>
                </div>
            </div>

            <div class="term-glosses">
                <div class="term-gloss-wrapper" v-for="(gloss, index) in featuredTerm.glosses" :key="gloss.id">
                    <GlossItem :gloss="gloss" :position="index + 1"/>

                    <div class="tf-sentence" v-for="sentence in gloss.sentences" :key="sentence.id">
                        <div class="sentence">{{ sentence.sentence }}</div>
                        <div class="translation">{{ sentence.trans }}</div>
                    </div>
                </div>
            </div>

<!--            <div v-if="inflections.length">-->
<!--                {{ inflections }}-->
<!--            </div>-->

            <ZatuneComment v-if="featuredTerm.usage" :comment="featuredTerm.usage"/>

            <div v-if="featuredTerm.image" class="zatune-image">
                <ZatuneNeutral/>
                <img :src="featuredTerm.image" alt="term image"/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.featured-term-container {
    display: grid;
    height: 100vh;
    place-items: center;
}

.featured-term-wrapper {
    width: min(100%, 48rem);
    display: grid;
    gap: 3.2rem;
    align-content: start;
}

.term-headword {
    padding: 0;
    color: var(--color-dark-secondary);

    .term-headword-arb {
        font-size: 4.8rem;
        font-weight: 400;
        font-family: var(--display-font);
    }
}


.term-glosses :deep {
    display: grid;
    gap: 6.4rem;

    .term-gloss-wrapper {
        display: grid;
        gap: 3.2rem;
    }

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
    margin-block: 1.6rem;
    padding: 0;
}

.zatune-image {
    width: 100%;
    display: grid;
    gap: 1.6rem;
    margin-block: 1.6rem;
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
        margin-block-end: 3.2rem;
    }
}
</style>
