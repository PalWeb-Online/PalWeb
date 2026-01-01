<script setup>
import {computed, ref} from "vue";
import {route} from 'ziggy-js';
import {useTerm} from "../composables/Term.js";
import TermActions from "./Actions/TermActions.vue";
import PinButton from "./PinButton.vue";
import TermDeckToggleButton from "./TermDeckToggleButton.vue";
import PronunciationItem from "./PronunciationItem.vue";
import SentenceItem from "./SentenceItem.vue";
import DeckItem from "./DeckItem.vue";
import ChartCliticization from "./Charts/ChartCliticization.vue";
import ChartInflection from "./Charts/ChartInflection.vue";
import ChartConjugation from "./Charts/ChartConjugation.vue";
import DialogLine from "./Charts/DialogLine.vue";
import LoadingSpinner from "../Shared/LoadingSpinner.vue";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

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
const loadingSentences = ref(new Map());

const fetchSentences = async (glossId) => {
    loadingSentences.value.set(glossId, true);

    try {
        const gloss = term.glosses.find(gloss => gloss.id === glossId);

        const response = await axios.get(route('terms.get.sentences', {term: term.id, gloss: glossId}));
        const sentences = response.data.filter(sentence =>
            !gloss.sentences.some(existing => existing.id === sentence.id)
        );

        gloss.sentences.push(...sentences);
        sentencesFetched.value.set(glossId, true);
        showSentences.value.set(glossId, true);
        loadingSentences.value.set(glossId, false);

    } catch (error) {
        console.error('Error fetching Pronunciations:', error);
        loadingSentences.value.set(glossId, false);
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

const derivatives = computed(() =>
    term.relatives.filter(relative => ['ap', 'pp', 'vn'].includes(relative.type)) ?? []
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

const etymology = computed(() => {
    const data = {};

    data.origin = `<span style="text-transform: capitalize">${term.etymology.type}</span>${term.etymology.source ? ` <span style="font-style: italic">${term.etymology.source}</span>` : ''}. `;

    data.singPatterns = term.patterns
        .filter(pattern => pattern.type === 'singular')
        .map(pattern =>
            `In the ${pattern.form ? `<b>Form ${pattern.form}</b>` : ''} <b ${pattern.form ? `style="text-transform: uppercase"` : ''}>${pattern.pattern}</b> pattern. `
        ).join('');

    data.plurPatterns = term.patterns
        .filter(pattern => pattern.type === 'plural')
        .map(pattern =>
            `Has a <b>${pattern.pattern}</b> ${['-īn', '-āt'].includes(pattern.pattern) ? 'sound' : 'broken'} plural. `
        ).join('');

    const components = term.relatives.filter(relative => relative.type === 'component') ?? [];
    data.components = [];

    if (components.length > 0) {
        data.components.push({type: 'text', value: 'Idiom from '});
        components.forEach((comp, index) => {
            data.components.push({
                type: 'link',
                slug: comp.slug,
                label: `${comp.term} (${comp.translit})`,
            });
            data.components.push({
                type: 'text',
                value: index < components.length - 1 ? ', ' : '. ',
            });
        });
    }

    const descendants = term.relatives.filter(relative => relative.type === 'descendant') ?? [];
    data.descendants = [];

    if (descendants.length > 0) {
        data.descendants.push({type: 'text', value: `Component of `});
        descendants.forEach((comp, index) => {
            data.descendants.push({
                type: 'link',
                slug: comp.slug,
                label: `${comp.term} (${comp.translit})`,
            });
            data.descendants.push({
                type: 'text',
                value: index < descendants.length - 1 ? ', ' : '. ',
            });
        });
    }

    const derivative = term.patterns.find(pattern => pattern.type === 'singular' && pattern.form)?.pattern;
    const derivativeMap = {
        ap: 'Active Participle',
        pp: 'Passive Participle',
        vn: 'Verbal Noun',
    };

    const source = term.relatives.find(relative => relative.type === 'source');
    data.source = [];

    if (source) {
        data.source.push({type: 'text', value: `<b>${derivativeMap[derivative]}</b> of `});
        data.source.push({
            type: 'link',
            slug: source.slug,
            label: `${source.term} (${source.translit})`,
        });
        data.source.push({type: 'text', value: '. '});
    }

    return data;
});
</script>

<template>
    <template v-if="! isLoading">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('terms.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/terms/{term}</div>
                <TermDeckToggleButton :model="term"/>
                <Link :href="route('terms.random')" class="material-symbols-rounded">keyboard_double_arrow_right</Link>
            </div>
            <div class="window-section-head">
                <h1>term</h1>
                <PinButton modelType="term" :model="term"/>
                <TermActions :model="term"/>
            </div>
            <div class="term-container-head">
                <div class="term-headword">
                    <div class="term-headword-term">
                        <div class="term-headword-arb">{{ term.term }}</div>
                        <div class="term-headword-eng">({{ term.translit }})</div>
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
                                    <span> </span>
                                </a>
                            </template>
                            <template v-else-if="['idiom', 'clitic'].includes(attribute.attribute)">
                                <span style="font-weight: 400; font-style: italic">
                                    {{ attribute.attribute }}.
                                    <span> </span>
                                </span>
                            </template>
                            <template v-else>
                                <span style="font-weight: 400">
                                    {{ attribute.attribute }}.
                                    <span> </span>
                                </span>
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
                    <div v-if="references.length > 0">
                        <div>see:</div>
                        <Link v-for="reference in references" :href="route('terms.show', reference.slug)">
                            {{ reference.term }} ({{ reference.translit }})
                        </Link>
                    </div>
                    <div v-if="variants.length > 0">
                        <div>alt.</div>
                        <Link v-for="variant in variants" :href="route('terms.show', variant.slug)">
                            {{ variant.term }} ({{ variant.translit }})
                        </Link>
                    </div>
                    <div v-if="term.spellings.length > 0">
                        <div>or</div>
                        <div v-for="spelling in term.spellings" style="font-weight: 700">
                            {{ spelling.spelling }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="window-section-head">
                <h2>info</h2>
            </div>
            <div class="term-etymology">
                <Link class="term-root" v-if="term.root" :href="route('roots.show', term.root.id )">
                    <div class="term-root-ar">{{ term.root.ar }}</div>
                    <div class="term-root-en">({{ term.root.en }})</div>
                </Link>
                <div class="term-data">
                    <!-- todo: link to Wiki page with info on Patterns -->

                    <span v-html="etymology.origin"></span>

                    <span v-for="(token, index) in etymology.source" :key="index">
                            <span v-if="token.type === 'text'" v-html="token.value"></span>
                            <Link v-else-if="token.type === 'link'" :href="route('terms.show', token.slug)">
                              {{ token.label }}
                            </Link>
                        </span>

                    <span v-html="etymology.singPatterns"></span>
                    <span v-html="etymology.plurPatterns"></span>

                    <span v-for="(token, index) in etymology.components" :key="index">
                            <span v-if="token.type === 'text'" v-html="token.value"></span>
                            <Link v-else-if="token.type === 'link'" :href="route('terms.show', token.slug)">
                              {{ token.label }}
                            </Link>
                        </span>

                    <span v-for="(token, index) in etymology.descendants" :key="index">
                            <span v-if="token.type === 'text'" v-html="token.value"></span>
                            <Link v-else-if="token.type === 'link'" :href="route('terms.show', token.slug)">
                              {{ token.label }}
                            </Link>
                        </span>
                </div>
            </div>
            <div class="term-pronunciation">
                <div class="model-list">
                    <PronunciationItem v-if="showPronunciations" v-for="pronunciation in term.pronunciations"
                                       :model="pronunciation"/>
                    <PronunciationItem v-else :model="term.pronunciations[0]"/>
                </div>
                <button v-if="term.pronunciations_count > 1 && !pronunciationsFetched"
                        @click="fetchPronunciations" class="material-symbols-rounded">
                    keyboard_arrow_down
                </button>
                <button v-else-if="term.pronunciations_count > 1 && pronunciationsFetched && showPronunciations"
                        @click="showPronunciations = false" class="material-symbols-rounded">
                    keyboard_arrow_up
                </button>
                <button v-else-if="term.pronunciations_count > 1 && pronunciationsFetched && !showPronunciations"
                        @click="showPronunciations = true" class="material-symbols-rounded">
                    keyboard_arrow_down
                </button>
            </div>

            <div class="window-section-head">
                <h2>glosses</h2>
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
                            <div class="gloss-item-relatives" v-if="glossRelatives(gloss.id, ['synonym']).length > 0">
                                syn.
                                <Link v-for="synonym in glossRelatives(gloss.id, ['synonym'])" :key="synonym.id"
                                      :href="route('terms.show', synonym.slug)">
                                    {{ synonym.term }}
                                    ({{ synonym.translit }})
                                </Link>
                            </div>
                            <div class="gloss-item-relatives" v-if="glossRelatives(gloss.id, ['antonym']).length > 0">
                                ant.
                                <Link v-for="antonym in glossRelatives(gloss.id, ['antonym'])" :key="antonym.id"
                                      :href="route('terms.show', antonym.slug)">
                                    {{ antonym.term }}
                                    ({{ antonym.translit }})
                                </Link>
                            </div>
                            <div class="gloss-item-relatives"
                                v-if="glossRelatives(gloss.id, ['isPatient', 'noPatient', 'hasObject']).length > 0"
                                v-for="pair in glossRelatives(gloss.id, ['isPatient', 'noPatient', 'hasObject'])"
                                :key="pair.id"
                            >
                                {{ pair.type }}
                                <Link :href="route('terms.show', pair.slug)">{{ pair.term }}
                                    ({{ pair.translit }})
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="gloss.sentences.length > 0" class="model-list">
                        <SentenceItem v-if="showSentences.get(gloss.id)" v-for="sentence in gloss.sentences"
                                      :model="sentence"
                                      :currentTerm="term.id" dialog/>
                        <SentenceItem v-else :model="gloss.sentences[0]" :currentTerm="term.id" dialog/>

                        <div class="model-list-toggle-expand"
                             v-if="gloss.sentences_count > 1 && !sentencesFetched.get(gloss.id)">
                            <button @click="fetchSentences(gloss.id)">See All ({{ gloss.sentences_count }})</button>
                            <LoadingSpinner v-show="loadingSentences.get(gloss.id)"/>
                        </div>

                        <div class="model-list-toggle-expand" v-if="sentencesFetched.get(gloss.id)">
                            <button @click="toggleShowSentences(gloss.id)">
                                {{ showSentences.get(gloss.id) ? 'Hide' : 'Expand' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <ChartConjugation
                v-if="term.category === 'verb' && !term.attributes.map(attribute => attribute.attribute).includes('idiom') && !hostForms.length"
                :roots="term.root.all"
                :patterns="term.patterns"
                :derivatives="derivatives"
            />

            <ChartInflection
                v-if="['noun', 'adjective'].includes(term.category) && inflections.length > 0"
                :term="term"
                :inflections="inflections"
            />
            <ChartCliticization
                v-if="hostForms.length > 0"
                :inflections="hostForms"
            />

            <div class="chart-dialog" v-if="responseForms.length > 0">
                <DialogLine speaker="دعاء" :ar="term.term" :en="term.translit"/>
                <DialogLine speaker="جواب" align="ltr" v-for="response in responseForms"
                            :ar="response.inflection"
                            :en="response.translit"/>
            </div>

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

            <div class="term-image">
                <img v-if="term.image" :src="term.image" alt="Term Image">
            </div>

            <div v-if="term.decks.length > 0" class="term-container-decks">
                <div class="featured-title m">Featured In</div>
                <div class="model-list index-list">
                    <DeckItem v-for="deck in term.decks" :model="deck"/>
                </div>
            </div>
        </div>
    </template>
</template>
