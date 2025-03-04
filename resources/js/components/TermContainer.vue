<script setup>
import {Link} from '@inertiajs/inertia-vue3'
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
import DialogContainer from "./DialogContainer.vue";
import ChartConjugation from "./charts/ChartConjugation.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    root: {
        type: Object,
        required: false,
        default: null
    },
});

// todo: consider creating TermStore for Show page & Term Editor. (how would this work with >1 Terms per page?)
const {data, fetchPronunciations, fetchSentences} = useTerm(props);

const attributeLinks = {
    collective: {text: "nouns", url: route("wiki.show", "nouns")},
    demonym: {text: "adjectives", url: route("wiki.show", "adjectives")},
    defect: {text: "adjectives", url: route("wiki.show", "adjectives")},
    pseudo: {text: "verbs", url: route("wiki.show", "verbs")},
};
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="term-container">
            <div class="term-container-head">
                <div class="term-headword">
                    <div class="term-headword-term">
                        <PinButton modelType="term" :model="data.term"/>
                        <div class="term-headword-arb">{{ data.term.term }}</div>
                        <div class="term-headword-eng">({{ data.term.translit }})</div>
                        <TermActions :model="data.term"/>
                        <TermDeckToggleButton :model="data.term"/>
                    </div>

                    <div class="term-headword-data">{{ data.term.category }}.
                        <template v-for="attribute in data.term.attributes" :key="attribute">
                            <template v-if="attributeLinks[attribute]">
                                <a
                                    :href="attributeLinks[attribute].url"
                                    target="_blank"
                                    class="attribute-link"
                                >
                                    {{ attribute }}.
                                </a>
                            </template>
                            <template v-else-if="['idiom', 'clitic'].includes(attribute)">
                                <span style="font-weight: 400; font-style: italic">
                                    {{ attribute }}.
                                </span>
                            </template>
                            <template v-else>
                                <span style="font-weight: 400">{{ attribute }}.</span>
                            </template>
                        </template>
                        <template v-if="data.term.inflections.construct.length > 0">
                            <span style="font-weight: 400">construct:</span>
                            {{ data.term.inflections.construct[0].inflection }}
                            ({{ data.term.inflections.construct[0].translit }})
                        </template>
                        <template v-if="data.term.category === 'verb'">
                            <a v-for="pattern in data.term.patterns"
                               :href="route('wiki.show', 'verb-forms')" target="_blank" style="font-style: italic">
                                form {{ pattern.form }}.</a>
                        </template>
                    </div>
                </div>
                <div class="term-references"
                     v-if="data.term.spellings.length + data.term.variants.length + data.term.references.length > 0">
                    <div v-if="data.term.spellings.length > 0">
                        <div>or</div>
                        <div v-for="spelling in data.term.spellings" style="font-weight: 700">
                            {{ spelling.spelling }}
                        </div>
                    </div>

                    <div v-if="data.term.variants.length > 0">
                        <div>alt.</div>
                        <Link v-for="variant in data.term.variants" :href="route('terms.show', variant.slug)">
                            {{ variant.term }} ({{ variant.translit }})
                        </Link>
                    </div>

                    <div v-if="data.term.references.length > 0">
                        <div>see:</div>
                        <Link v-for="reference in data.term.references" :href="route('terms.show', reference.slug)">
                            {{ reference.term }} ({{ reference.translit }})
                        </Link>
                    </div>
                </div>
            </div>

            <div class="pronunciations-list">
                <PronunciationItem v-for="pronunciation in data.term.pronunciations"
                                   :model="pronunciation"/>

                <!--                todo: load / toggle -->
                <button v-if="data.term.pronunciations_count > 1" @click="fetchPronunciations">+</button>
            </div>

            <div class="term-etymology">
                <div class="featured-title">etymology</div>
                <div>
                    <span style="text-transform: capitalize">{{ data.term.etymology.type }}</span><span
                    v-if="data.term.etymology.source" style="font-style: italic"> {{
                        data.term.etymology.source
                    }}</span>.

                    <!-- todo: link to Wiki page with info on Patterns;
                            weird spacing; plural may appear before singular (شيكل) -->
                    <template v-for="pattern in data.term.patterns">
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

                    <template v-if="data.term.components.length > 0">
                        Idiom from
                        <template v-for="(component, index) in data.term.components">
                            <Link :href="route('terms.show', component.slug)">
                                {{ component.term }} ({{ component.translit }})
                            </Link>
                            {{ index < data.term.components.length - 1 ? ', ' : '.' }}
                        </template>
                    </template>

                    <template v-if="data.term.descendants.length > 0">
                        Component of
                        <template v-for="(descendant, index) in data.term.descendants">
                            <Link :href="route('terms.show', descendant.slug)">
                                {{ descendant.term }} ({{ descendant.translit }})
                            </Link>
                            {{ index < data.term.descendants.length - 1 ? ', ' : '.' }}
                        </template>
                    </template>
                </div>
            </div>

            <div class="term-container-glosses">
                <div v-for="(gloss, index) in data.term.glosses" class="gloss-li-container">
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
                            <div v-if="gloss.synonyms.length > 0">
                                syn.
                                <Link v-for="synonym in gloss.synonyms" :href="route('terms.show', synonym.slug)">
                                    {{ synonym.term }}
                                    ({{ synonym.translit }})
                                </Link>
                            </div>
                            <div v-if="gloss.antonyms.length > 0">
                                ant.
                                <Link v-for="antonym in gloss.antonyms" :href="route('terms.show', antonym.slug)">
                                    {{ antonym.term }}
                                    ({{ antonym.translit }})
                                </Link>
                            </div>
                            <div v-for="pair in gloss.valences">
                                {{ pair.type }}
                                <Link :href="route('terms.show', pair.slug)">{{ pair.term }}
                                    ({{ pair.translit }})
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="gloss.sentences.length > 0" class="sentences-list">
                        <!-- todo: can't tell if the dialog flag is working; Dialogger is bugging out-->
                        <SentenceContainer v-for="sentence in gloss.sentences" :model="sentence"
                                           :currentTerm="data.term.id" dialog/>

                        <!--                todo: load / toggle; add sentences_count -->
                        <button class="see-all" @click="fetchSentences(gloss.id)">See All Sentences (?)</button>
                    </div>
                </div>
            </div>

            <ChartConjugation
                v-if="data.term.category === 'verb'"
                :roots="root.all"
                :patterns="data.term.patterns"
            />

            <ChartInflection
                v-if="['verb', 'noun', 'adjective'].includes(data.term.category) && data.term.inflections.other.length > 0"
                :term="data.term"
                :inflections="data.term.inflections.other"
            />
            <ChartEnclitic
                v-if="data.term.inflections.host.length > 0"
                :inflections="data.term.inflections.host"
            />

<!--             todo: DialogContainer slot for one-off DialogLine items not associated with a Sentence. -->
            <!--            <DialogContainer v-if="data.term.inflections.response.length > 0">-->
            <!--                <x-dialog-line speaker="دعاء" :arb="$term->term" :eng="$term->translit" audio/>-->
            <!--                <x-dialog-line v-for="response in data.term.inflections.response" ltr speaker="جواب" :arb="$response->inflection" :eng="$response->translit" audio/>-->
            <!--            </DialogContainer>-->

            <figure v-if="data.term.image" class="term-image">
                <img alt="Term Image" src="{{ data.term.image }}">
            </figure>

            <!--            note that my user is hard-coded -->
            <div v-if="data.term.usage" class="user-wrapper">
                <div class="user-avatar">
                    <img src="/img/avatars/character02.jpg"
                         alt="Profile Picture"/>
                </div>
                <div class="user-comment">
                    <div class="user-comment-head">Editor's Note</div>
                    <div class="user-comment-body">
                        <div class="user-comment-body-content">
                            {{ data.term.usage }}
                        </div>
                        <div class="user-comment-body-data">
                            — R. Adrian (permanent.intifada)
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="data.term.decks.length > 0" class="term-container-decks">
                <div class="featured-title s">Featured In</div>
                <div class="decks-list">
                    <DeckItem v-for="deck in data.term.decks" :model="deck"/>
                </div>
            </div>
        </div>
    </template>
</template>
