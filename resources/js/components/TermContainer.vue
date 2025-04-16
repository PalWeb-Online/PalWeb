<script setup>
import {computed, ref} from "vue";
import {route} from 'ziggy-js';
import {useTerm} from "../composables/Term.js";
import TermActions from "./TermActions.vue";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import PronunciationItem from "./PronunciationItem.vue";
import SentenceContainer from "./SentenceContainer.vue";
import DeckItem from "./DeckItem.vue";
import ChartEnclitic from "./charts/ChartEnclitic.vue";
import ChartInflection from "./charts/ChartInflection.vue";
import ChartConjugation from "./charts/ChartConjugation.vue";
import DialogLine from "./charts/DialogLine.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const {term, isLoading} = useTerm(props);

const showPronunciations = ref(false);
const pronunciationsFetched = ref(false);

const fetchPronunciations = async () => {
    try {
        const response = await axios.get(route('terms.get.pronunciations', {term: term.id}));
        const pronunciations = response.data.filter(pronunciation =>
            !term.pronunciations.some(existing => existing.id === pronunciation.id)
        );

        term.pronunciations.push(...pronunciations);
        pronunciationsFetched.value = true;
        showPronunciations.value = true;

    } catch (error) {
        console.error('Error fetching Pronunciations:', error);
    }
}

const showSentences = ref(new Map());
const sentencesFetched = ref(new Map());

const fetchSentences = async (glossId) => {
    try {
        const gloss = term.glosses.find(gloss => gloss.id === glossId);

        const response = await axios.get(route('terms.get.sentences', {term: term.id, gloss: glossId}));
        const sentences = response.data.filter(sentence =>
            !gloss.sentences.some(existing => existing.id === sentence.id)
        );

        gloss.sentences.push(...sentences);
        sentencesFetched.value.set(glossId, true);
        showSentences.value.set(glossId, true);

    } catch (error) {
        console.error('Error fetching Pronunciations:', error);
    }
}

const toggleShowSentences = (glossId) => {
    const currentState = showSentences.value.get(glossId) || false;
    showSentences.value.set(glossId, !currentState);
};

const variants = computed(() =>
    term.relatives.filter(relative => relative.type === "variant") ?? []
);

const references = computed(() =>
    term.relatives.filter(relative => relative.type === "reference") ?? []
);

const components = computed(() =>
    term.relatives.filter(relative => relative.type === "component") ?? []
);

const descendants = computed(() =>
    term.relatives.filter(relative => relative.type === "descendant") ?? []
);

const hostForms = computed(() =>
    term.inflections.filter(inflection => ['genitive', 'accusative'].includes(inflection.form)) ?? []
);

const responseForms = computed(() =>
    term.inflections.filter(inflection => inflection.form === 'resp') ?? []
);

const constructForms = computed(() =>
    term.inflections.filter(inflection => inflection.form === 'cnst') ?? []
);

const inflections = computed(() =>
    term.inflections.filter(inflection => !['cnst', 'resp', 'genitive', 'accusative'].includes(inflection.form)) ?? []
);

const glossRelatives = (glossId, types) => {
    return term.relatives.filter(relative => {
        return relative.gloss_id === glossId && types.includes(relative.type);
    });
};

const attributeLinks = {
    collective: {text: "nouns", url: route("wiki.show", "nouns")},
    demonym: {text: "adjectives", url: route("wiki.show", "adjectives")},
    defect: {text: "adjectives", url: route("wiki.show", "adjectives")},
    pseudo: {text: "verbs", url: route("wiki.show", "verbs")},
};
</script>

<template>
    <template v-if="! isLoading">
        <div class="term-container">
            <div class="term-container-head">
                <div class="term-headword">
                    <div class="term-headword-term">
                        <PinButton modelType="term" :model="term"/>
                        <div class="term-headword-arb">{{ term.term }}</div>
                        <div class="term-headword-eng">({{ term.translit }})</div>
                        <TermActions :model="term"/>
                        <TermDeckToggleButton :model="term"/>
                    </div>

                    <div class="term-headword-data">{{ term.category }}.
                        <template v-for="attribute in term.attributes" :key="attribute.id">
                            <template v-if="attributeLinks[attribute.attribute]">
                                <a
                                    :href="attributeLinks[attribute.attribute].url"
                                    target="_blank"
                                    class="attribute-link"
                                >
                                    {{ attribute.attribute }}.
                                </a>
                            </template>
                            <template v-else-if="['idiom', 'clitic'].includes(attribute.attribute)">
                                <span style="font-weight: 400; font-style: italic">
                                    {{ attribute.attribute }}.
                                </span>
                            </template>
                            <template v-else>
                                <span style="font-weight: 400">{{ attribute.attribute }}.</span>
                            </template>
                        </template>
                        <template v-if="constructForms.length > 0">
                            <span style="font-weight: 400">construct:</span>
                            {{ constructForms[0].inflection }}
                            ({{ constructForms[0].translit }})
                        </template>
                        <template v-if="term.category === 'verb'">
                            <a v-for="pattern in term.patterns"
                               :href="route('wiki.show', 'verb-forms')" target="_blank" style="font-style: italic">
                                form {{ pattern.form }}.</a>
                        </template>
                    </div>
                </div>
                <div class="term-references"
                     v-if="term.spellings.length + variants.length + references.length > 0">
                    <div v-if="term.spellings.length > 0">
                        <div>or</div>
                        <div v-for="spelling in term.spellings" style="font-weight: 700">
                            {{ spelling.spelling }}
                        </div>
                    </div>

                    <div v-if="variants.length > 0">
                        <div>alt.</div>
                        <Link v-for="variant in variants" :href="route('terms.show', variant.slug)">
                            {{ variant.term }} ({{ variant.translit }})
                        </Link>
                    </div>

                    <div v-if="references.length > 0">
                        <div>see:</div>
                        <Link v-for="reference in references" :href="route('terms.show', reference.slug)">
                            {{ reference.term }} ({{ reference.translit }})
                        </Link>
                    </div>
                </div>
            </div>

            <div class="pronunciations-list">
                <PronunciationItem v-if="showPronunciations" v-for="pronunciation in term.pronunciations" :model="pronunciation"/>
                <PronunciationItem v-else :model="term.pronunciations[0]"/>

                <button v-if="term.pronunciations_count > 1 && !pronunciationsFetched" @click="fetchPronunciations">+</button>
                <button v-else-if="term.pronunciations_count > 1 && pronunciationsFetched && showPronunciations" @click="showPronunciations = false">-</button>
                <button v-else-if="term.pronunciations_count > 1 && pronunciationsFetched && !showPronunciations" @click="showPronunciations = true">+</button>
            </div>

            <div class="term-etymology">
                <div class="featured-title">etymology</div>
                <div class="term-etymology-content">
                    <Link class="term-root" v-if="term.root" :href="route('roots.show', term.root.id )">
                        <div class="term-root-ar">{{ term.root.ar }}</div>
                        <div class="term-root-en">({{ term.root.en }})</div>
                    </Link>
                    <div class="term-data">
                        <span style="text-transform: capitalize">{{ term.etymology.type }}</span>
                        <span v-if="term.etymology.source" style="font-style: italic"> {{
                                term.etymology.source
                            }}</span>.

                        <!-- todo: link to Wiki page with info on Patterns;
                                weird spacing; plural may appear before singular (شيكل) -->
                        <template v-for="pattern in term.patterns">
                            <template v-if="pattern.type === 'singular'">
                                In the
                                <b v-if="pattern.form">Form {{ pattern.form }}</b>
                                <b>{{ pattern.pattern }}</b>
                                pattern.
                            </template>
                            <template v-if="pattern.type === 'plural'">
                                Has a
                                <b>{{ pattern.pattern }}</b>
                                {{ ['-īn', '-āt'].includes(pattern.pattern) ? 'sound' : 'broken' }}
                                plural.
                            </template>
                        </template>

                        <template v-if="components.length > 0">
                            Idiom from
                            <template v-for="(component, index) in components">
                                <Link :href="route('terms.show', component.slug)">
                                    {{ component.term }} ({{ component.translit }})
                                </Link>
                                {{ index < components.length - 1 ? ', ' : '.' }}
                            </template>
                        </template>

                        <template v-if="descendants.length > 0">
                            Component of
                            <template v-for="(descendant, index) in descendants">
                                <Link :href="route('terms.show', descendant.slug)">
                                    {{ descendant.term }} ({{ descendant.translit }})
                                </Link>
                                {{ index < descendants.length - 1 ? ', ' : '.' }}
                            </template>
                        </template>
                    </div>
                </div>
            </div>

            <div class="term-container-glosses">
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
                            <div v-if="glossRelatives(gloss.id, ['synonym']).length > 0">
                                syn.
                                <Link v-for="synonym in glossRelatives(gloss.id, ['synonym'])" :key="synonym.id"
                                      :href="route('terms.show', synonym.slug)">
                                    {{ synonym.term }}
                                    ({{ synonym.translit }})
                                </Link>
                            </div>
                            <div v-if="glossRelatives(gloss.id, ['antonym']).length > 0">
                                ant.
                                <Link v-for="antonym in glossRelatives(gloss.id, ['antonym'])" :key="antonym.id"
                                      :href="route('terms.show', antonym.slug)">
                                    {{ antonym.term }}
                                    ({{ antonym.translit }})
                                </Link>
                            </div>
                            <div
                                v-if="glossRelatives(gloss.id, ['isPatient', 'noPatient', 'hasObject']).length > 0"
                                v-for="pair in glossRelatives(gloss.id, ['isPatient', 'noPatient', 'hasObject'])" :key="pair.id"
                            >
                                {{ pair.type }}
                                <Link :href="route('terms.show', pair.slug)">{{ pair.term }}
                                    ({{ pair.translit }})
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="gloss.sentences.length > 0" class="sentences-list">
                        <SentenceContainer v-if="showSentences.get(gloss.id)" v-for="sentence in gloss.sentences" :model="sentence"
                                           :currentTerm="term.id" dialog/>
                        <SentenceContainer v-else :model="gloss.sentences[0]" :currentTerm="term.id" dialog/>

                        <button class="see-all" v-if="gloss.sentences_count > 1 && !sentencesFetched.get(gloss.id)"
                                @click="fetchSentences(gloss.id)">See All ({{ gloss.sentences_count }})</button>

                        <button class="see-all" v-if="sentencesFetched.get(gloss.id)"
                                @click="toggleShowSentences(gloss.id)">
                            {{ showSentences.get(gloss.id) ? 'Hide' : 'Expand' }}
                        </button>
                    </div>
                </div>
            </div>

            <ChartConjugation
                v-if="term.category === 'verb' && !hostForms.length"
                :roots="term.root.all"
                :patterns="term.patterns"
            />

            <ChartInflection
                v-if="['verb', 'noun', 'adjective'].includes(term.category) && inflections.length > 0"
                :term="term"
                :inflections="inflections"
            />
            <ChartEnclitic
                v-if="hostForms.length > 0"
                :inflections="hostForms"
            />

            <div class="chart-dialog" v-if="responseForms.length > 0">
                <DialogLine speaker="دعاء" :ar="term.term" :en="term.translit"/>
                <DialogLine speaker="جواب" align="ltr" v-for="response in responseForms"
                            :ar="response.inflection"
                            :en="response.translit"/>
            </div>

            <figure v-if="term.image" class="term-image">
                <img alt="Term Image" :src="term.image">
            </figure>

            <!--            note that my user is hard-coded -->
            <div v-if="term.usage" class="user-item m">
                <Link class="user-avatar" :href="route('users.show', 'permanent.intifada')">
                    <img src="/img/avatars/character02.jpg"
                         alt="Profile Picture"/>
                </Link>
                <div class="user-data-wrapper">
                    <div class="user-name">
                        <div class="user-name-en">
                            <div>Editor's Note</div>
                        </div>
                    </div>
                    <div class="user-comment">
                        <div class="user-comment-content">
                            {{ term.usage }}
                        </div>
                        <div class="user-comment-data">
                            — R. Adrian (permanent.intifada)
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="term.decks.length > 0" class="term-container-decks">
                <div class="featured-title s">Featured In</div>
                <div class="decks-list">
                    <DeckItem v-for="deck in term.decks" :model="deck"/>
                </div>
            </div>
        </div>
    </template>
</template>
