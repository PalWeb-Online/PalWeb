<script>
export default {
    props: [
        'mode',
        'termId'
    ],

    computed: {
        updateRoute() {
            return `/dictionary/terms/admin/${this.termId}/update`;
        },
        createRoute() {
            return `/dictionary/terms/admin/store`;
        }
    },

    data() {
        return {
            term: {
                term: '',
                category: '',
                etymology: {
                    type: '',
                    source: '',
                },
                image: '',
                usage: '',
            },
            root: '',
            attributes: [],
            singPatterns: [],
            plurPatterns: [],
            verbPatterns: [],
            pronunciations: [],
            variants: [],
            components: [],
            references: [],
            spellings: [],
            inflections: [],
            glosses: [],
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            errors: null,
        };
    },
    created() {
        this.addPronunciation();
        this.addGloss();
        if (this.mode == "edit") {
            this.getTerm(this.termId);
        }
    },
    methods: {
        getTerm(termId) {
            axios.get("/dictionary/terms/admin/" + termId + "/get")
                .then(response => {
                    this.term = response.data.term;
                    this.root = (response.data.root) ? response.data.root : this.root;
                    this.attributes = response.data.attributes;
                    this.singPatterns = response.data.singPatterns;
                    this.plurPatterns = response.data.plurPatterns;
                    this.verbPatterns = response.data.verbPatterns;
                    this.pronunciations = response.data.pronunciations;
                    this.variants = response.data.variants;
                    this.components = response.data.components;
                    this.references = response.data.references;
                    this.spellings = (response.data.spellings) ? response.data.spellings : this.spellings;
                    this.inflections = (response.data.inflections) ? response.data.inflections : this.inflections;
                    this.glosses = response.data.glosses;
                })
                .catch(error => {
                    this.errors = error.response.data['message']
                });
        },
        hasPattern(category) {
            const allowedCategories = ['verb', 'noun', 'adjective', 'numeral'];
            return allowedCategories.includes(category);
        },
        hasPlural(category) {
            const allowedCategories = ['noun', 'adjective', 'numeral'];
            return allowedCategories.includes(category);
        },
        hasInflection(category) {
            const allowedCategories = ['verb', 'noun', 'adjective', 'adverb', 'numeral', 'preposition', 'particle', 'phrase'];
            return allowedCategories.includes(category);
        },

        addItem(itemName) {
            this[itemName].push('')
        },
        removeItem(itemName, index) {
            this[itemName].splice(index, 1)
        },

        addPronunciation() {
            this.pronunciations.push({
                translit: '',
                phonemic: '//',
                phonetic: '[]',
                dialect_id: '1',
                borrowed: 0,
            });
        },
        removePronunciation(index, fieldType) {
            fieldType.splice(index, 1);
        },

        addSingPattern() {
            this.singPatterns.push({
                type: 'singular',
                form: '',
                pattern: '',
            });
        },
        removeSingPattern(index, fieldType) {
            fieldType.splice(index, 1);
        },

        addPlurPattern() {
            this.plurPatterns.push({
                type: 'plural',
                form: '',
                pattern: '',
            });
        },
        removePlurPattern(index, fieldType) {
            fieldType.splice(index, 1);
        },

        addVerbPattern() {
            this.verbPatterns.push({
                type: 'verbal',
                form: '',
                pattern: '',
            });
        },
        removeVerbPattern(index, fieldType) {
            fieldType.splice(index, 1);
        },

        addSpelling() {
            this.spellings.push({
                spelling: '',
            })
        },
        removeSpelling(index) {
            this.spellings.splice(index, 1)
        },

        addInflection() {
            this.inflections.push({
                inflection: '',
                translit: '',
                form: '',
            });
        },
        removeInflection(index, fieldType) {
            fieldType.splice(index, 1);
        },

        addGloss() {
            this.glosses.push({
                gloss: '',
                attribute: '',
                structure: '',
                synonyms: [],
                antonyms: [],
                valences: [],
            });
        },
        removeGloss(item) {
            this.glosses.splice(this.glosses.indexOf(item), 1);
        },

        addSynonym(gloss) {
            let item = this.glosses.find(itm => itm == gloss);
            item.synonyms.push('');
        },
        removeSynonym(index, fieldType) {
            fieldType.splice(index, 1)
        },

        addAntonym(gloss) {
            let item = this.glosses.find(itm => itm == gloss);
            item.antonyms.push('');
        },
        removeAntonym(index, fieldType) {
            fieldType.splice(index, 1)
        },

        addValence(gloss) {
            let item = this.glosses.find(itm => itm == gloss);
            item.valences.push({
                translit: '',
                valence: '',
            });
        },
        removeValence(index, fieldType) {
            fieldType.splice(index, 1)
        },

        submit() {
            if (this.mode === "add") {
                axios.post(this.createRoute, {
                    term: this.term,
                    root: this.root,
                    attributes: this.attributes,
                    singPatterns: this.singPatterns,
                    plurPatterns: this.plurPatterns,
                    verbPatterns: this.verbPatterns,
                    pronunciations: this.pronunciations,
                    variants: this.variants,
                    components: this.components,
                    references: this.references,
                    spellings: this.spellings,
                    inflections: this.inflections,
                    glosses: this.glosses
                })
                    .then(response => {
                        if (response.data.status == 'success') {
                            window.location = response.data.redirect;
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data['message']
                    });
            } else {
                axios.patch(this.updateRoute, {
                    term: this.term,
                    root: this.root,
                    attributes: this.attributes,
                    singPatterns: this.singPatterns,
                    plurPatterns: this.plurPatterns,
                    verbPatterns: this.verbPatterns,
                    pronunciations: this.pronunciations,
                    variants: this.variants,
                    components: this.components,
                    references: this.references,
                    spellings: this.spellings,
                    inflections: this.inflections,
                    glosses: this.glosses
                })
                    .then(response => {
                        if (response.data.status == 'success') {
                            window.location = response.data.redirect;
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data['message']
                    });
            }
        }
    }
};
</script>

<template>
    <form action="#" method="POST" @submit.prevent="submit()">
        <input :value="token" name="_token" type="hidden">
        <div v-if="errors" class="form-errors">
            <div>Something went wrong:</div>
            <ul>
                <li>{{ errors }}</li>
            </ul>
        </div>
        <div class="term-editor-body">
            <div>
                <div class="field-wrapper">
                    <div class="field-label">
                        <div>term</div>
                    </div>
                    <div class="form-field">
                        <label for="term[term]">Term *</label>
                        <input id="term[term]" v-model="term.term" name="term[term]" required type="text"/>
                    </div>

                    <div class="form-field inline">
                        <label for="term[category]">Category *</label>
                        <select id="term[category]" v-model="term.category" name="term[category]" required>
                            <option value="verb">verb</option>
                            <option value="noun">noun</option>
                            <option value="adjective">adjective</option>
                            <option value="numeral">numeral</option>
                            <option value="adverb">adverb</option>
                            <option value="preposition">preposition</option>
                            <option value="conjunction">conjunction</option>
                            <option value="determiner">determiner</option>
                            <option value="particle">particle</option>
                            <option value="phrase">phrase</option>
                            <option value="affix">affix</option>
                        </select>
                    </div>
                    <div class="field-wrapper with-add-field">
                        <div v-for="(value, index) in attributes" :key="index" class="form-field inline">
                            <label :for="'attributes['+index+']'">attribute</label>
                            <select :id="'attributes['+index+']'" v-model="attributes[index]"
                                    :name="'attributes['+index+']'">
                                <option selected value=""></option>
                                <option value="masculine">masculine</option>
                                <option value="feminine">feminine</option>
                                <option value="plural">plural</option>
                                <option value="collective">collective</option>
                                <option value="demonym">demonym</option>
                                <option value="defect">defect</option>
                                <option value="participle">participle</option>
                                <option value="pseudo">pseudo</option>
                                <option value="quantifier">quantifier</option>
                                <option value="complementizer">complementizer</option>
                                <option value="idiom">idiom</option>
                                <option value="clitic">clitic</option>
                                <option value="interrogative">interrogative</option>
                            </select>
                            <img src="/img/trash.svg" alt="Delete" v-show="attributes.length > 0"
                                 @click="removeItem('attributes', index)"/>
                        </div>
                        <div class="field-add" @click="addItem('attributes')">+ Add ATTRIBUTE</div>
                    </div>

                    <div class="form-field">
                        <label for="term[image]">Image URL</label>
                        <input id="term[image]" v-model="term.image" name="term[image]"
                               type="text"/>
                    </div>
                </div>

                <div class="field-wrapper with-add-object">
                    <div v-for="(pronunciation, index) in pronunciations" :key="index"
                         class="field-wrapper compound-field">
                        <div class="field-label">
                            <div>pronunciation</div>
                            <img src="/img/trash.svg" alt="Delete" v-show="pronunciations.length > 1"
                                 @click="removePronunciation(index, pronunciations)"/>
                        </div>
                        <div class="form-field">
                            <label :for="'pronunciations['+index+'][translit]'">Translit *</label>
                            <input :id="'pronunciations['+index+'][translit]'" v-model="pronunciation.translit"
                                   :name="'pronunciations['+index+'][translit]'" required
                                   type="text"/>
                        </div>
                        <div class="form-field">
                            <label :for="'pronunciations['+index+'][phonemic]'">Phonemic</label>
                            <input :id="'pronunciations['+index+'][phonemic]'" v-model="pronunciation.phonemic"
                                   :name="'pronunciations['+index+'][phonemic]'" type="text"/>
                        </div>
                        <div class="form-field">
                            <label :for="'pronunciations['+index+'][phonetic]'">Phonetic</label>
                            <input :id="'pronunciations['+index+'][phonetic]'" v-model="pronunciation.phonetic"
                                   :name="'pronunciations['+index+'][phonetic]'" type="text"/>
                        </div>
                        <div class="form-field">
                            <label :for="'pronunciations['+index+'][dialect_id]'">Dialect</label>
                            <select :id="'pronunciations['+index+'][dialect_id]'"
                                    v-model="pronunciation.dialect_id"
                                    :name="'pronunciations['+index+'][dialect_id]'">
                                <option value="1">General Palestinian</option>
                                <option value="2">Urban Palestinian</option>
                                <option value="3">Rural Palestinian</option>
                                <option value="4">Central Palestinian</option>
                                <option value="5">Northern Palestinian</option>
                                <option value="6">Palestinian Bedouin</option>
                                <option value="7">Palestinian Druze</option>
                                <option value="8">Central Urban Palestinian</option>
                                <option value="9">Northern Urban Palestinian</option>
                                <option value="10">Central Rural Palestinian</option>
                                <option value="11">Northern Rural Palestinian</option>
                            </select>
                        </div>
                        <label class="checkbox" :for="'pronunciations['+index+'][borrowed]'">
                            <span>Borrowed</span>
                            <input :id="'pronunciations['+index+'][borrowed]'" v-model="pronunciation.borrowed"
                                   :name="'pronunciations['+index+'][borrowed]'"
                                   type="checkbox"
                                   value=1/>
                        </label>
                    </div>
                    <div class="field-add" @click="addPronunciation()">+ Add PRONUNCIATION</div>
                </div>

                <div v-if="hasInflection(term.category)" class="field-wrapper with-add-object">
                    <div v-for="(inflection, index) in inflections" :key="index" class="field-wrapper compound-field">
                        <div class="field-label">
                            <div>inflection</div>
                            <img src="/img/trash.svg" alt="Delete" v-show="inflections.length > 0"
                                 @click="removeInflection(index, inflections)"/>
                        </div>
                        <div class="form-field inline">
                            <label :for="'inflections['+index+'][form]'">Type *</label>
                            <select :id="'inflections['+index+'][form]'" v-model="inflection.form"
                                    :name="'inflections['+index+'][form]'" required>
                                <template v-if="term.category === 'verb' && !attributes.includes('pseudo')">
                                    <option value="ap">AP</option>
                                    <option value="pp">PP</option>
                                    <option value="nv">NV</option>
                                </template>

                                <template v-if="term.category === 'noun'">
                                    <option v-if="attributes.includes('collective')" value="sing">singulative</option>
                                    <option v-if="attributes.includes('collective')" value="pauc">paucal</option>
                                    <option v-if="!attributes.includes('collective')" value="fem">feminine</option>
                                    <option value="plr">plural</option>
                                </template>
                                <option v-if="term.category === 'numeral'" value="cnst">construct</option>
                                <template
                                    v-if="term.category === 'adjective' || term.category === 'numeral' || term.category === 'particle'">
                                    <option value="fem">feminine</option>
                                    <option value="plr">plural</option>
                                </template>
                                <option v-if="term.category === 'adjective' && attributes.length === 0" value="elt">
                                    elative
                                </option>
                                <option value="host">host
                                </option>
                                <option v-if="term.category === 'phrase'" value="resp">response</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label :for="'inflections['+index+'][inflection]'">Inflection *</label>
                            <input :id="'inflections['+index+'][inflection]'" v-model="inflection.inflection"
                                   :name="'inflections['+index+'][inflection]'" required
                                   type="text"/>
                        </div>
                        <div class="form-field">
                            <label :for="'inflections['+index+'][translit]'">Translit *</label>
                            <input :id="'inflections['+index+'][translit]'" v-model="inflection.translit"
                                   :name="'inflections['+index+'][translit]'" required type="text"/>
                        </div>
                    </div>
                    <div class="field-add" @click="addInflection()">+ Add INFLECTION</div>
                </div>
            </div>
            <div>
                <div class="field-wrapper">
                    <div class="field-label">
                        <div>etymology</div>
                    </div>

                    <div v-if="attributes.includes('idiom')" class="field-wrapper with-add-field">
                        <div v-for="(value, index) in components" :key="index" class="form-field inline">
                            <label :for="'components['+index+']'">component</label>
                            <input :id="'components['+index+']'" v-model="components[index]"
                                   :name="'components['+index+']'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="components.length > 0"
                                 @click="removeItem('components', index)"/>
                        </div>
                        <div class="field-add" @click="addItem('components')">+ Add COMPONENT</div>
                    </div>

                    <template v-if="!attributes.includes(['idiom', 'clitic'])">
                        <div class="form-field">
                            <label for="root">Root{{ term.category === 'verb' && !attributes.includes('idiom') ? ' *' : '' }}</label>
                            <input id="root" v-model="root" :required="term.category === 'verb' && !attributes.includes('pseudo')"
                                   name="root"
                                   type="text"/>
                        </div>
                        <div class="form-field inline">
                            <label for="term[etymology-type]">Type *</label>
                            <select id="term[etymology-type]" v-model="term.etymology.type"
                                    name="term[etymology]" required>
                                <option value="inherited">inherited</option>
                                <option value="hybrid">hybrid</option>
                                <option value="borrowed">borrowed</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="term[etymology-source]">Source</label>
                            <input id="term[etymology-source]" v-model="term.etymology.source"
                                   name="term[etymology]" type="text"/>
                        </div>
                    </template>
                </div>
                <div v-if="hasPattern(term.category)" class="field-wrapper">
                    <div class="field-label">
                        <div>pattern</div>
                    </div>
                    <div v-if="term.category == 'verb'" class="field-wrapper with-add-field">
                        <div v-for="(verbPattern, index) in verbPatterns" :key="index" class="form-field inline">

                            <label :for="'verbPatterns['+index+'][form]'">Form *</label>
                            <select :id="'verbPatterns['+index+'][form]'" v-model="verbPattern.form"
                                    :name="'verbPatterns['+index+'][form]'" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="X">X</option>
                                <option value="2Q">2Q</option>
                                <option value="5Q">5Q</option>
                            </select>

                            <template v-if="verbPattern.form">
                                <label :for="'verbPatterns['+index+'][pattern]'">Pattern *</label>
                                <select :id="'verbPatterns['+index+'][pattern]'"
                                        v-model="verbPattern.pattern"
                                        :name="'verbPatterns['+index+'][pattern]'" required>
                                    <template v-if="verbPattern.form === '1'" label="Form 1">
                                        <option value="A1">A1</option>
                                        <option value="A1a">A1a</option>
                                        <option value="A1u">A1u</option>
                                        <option value="A2">A2</option>
                                        <option value="A2i">A2i</option>
                                        <option value="B1">B1</option>
                                        <option value="B1a">B1a</option>
                                        <option value="B2">B2</option>
                                        <option value="Ci">Ci</option>
                                        <option value="Ca">Ca</option>
                                        <option value="Cu">Cu</option>
                                        <option value="DY">DY</option>
                                        <option value="DA">DA</option>
                                        <option value="DW">DW</option>
                                        <option value="Ah">Ah</option>
                                        <option value="U1">U1</option>
                                        <option value="U2">U2</option>
                                    </template>
                                    <template v-if="verbPattern.form !== '1'">
                                        <option value="A">A</option>
                                    </template>
                                    <template v-if="verbPattern.form !== '1' && verbPattern.form !== '9'">
                                        <option value="B">B</option>
                                    </template>
                                    <template
                                        v-if="verbPattern.form === '4' || verbPattern.form === '7' || verbPattern.form === '8'">
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </template>
                                    <template v-if="verbPattern.form === '8'">
                                        <option value="E">E</option>
                                    </template>
                                </select>
                            </template>
                            <img src="/img/trash.svg" alt="Delete" v-show="verbPatterns.length > 0"
                                 @click="removeVerbPattern(index, verbPatterns)"/>
                        </div>
                        <div class="field-add" @click="addVerbPattern()">+ Add VERB PATTERN</div>
                    </div>

                    <div
                        v-if="term.category === 'noun' || term.category === 'adjective' || term.category === 'numeral'"
                        class="field-wrapper with-add-field">
                        <template v-for="(singPattern, index) in singPatterns" :key="index">
                            <div class="form-field inline">
                                <label for="singPatterns[form]">Form</label>
                                <select id="singPatterns[form]" v-model="singPattern.form"
                                        name="singPatterns[form]">
                                    <option selected value=""></option>
                                    <option value="1">1</option>
                                    <template v-if="term.category !== 'numeral'">
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="X">X</option>
                                        <option value="2Q">2Q</option>
                                        <option value="5Q">5Q</option>
                                    </template>
                                </select>
                            </div>
                            <div class="form-field inline">
                                <label for="singPatterns[pattern]">Singul *</label>
                                <select id="singPatterns[pattern]" v-model="singPattern.pattern"
                                        :required="attributes.includes('participle')"
                                        name="singPatterns[pattern]">
                                    <option v-if="attributes.includes('participle')" selected value="ap">AP</option>
                                    <template v-if="!attributes.includes('participle')">
                                        <option selected value=""></option>
                                        <optgroup v-if="singPattern.form" label="Derived Terms">
                                            <option value="ap">AP</option>
                                            <template
                                                v-if="term.category !== 'numeral' && !attributes.includes('participle')">
                                                <option value="pp">PP</option>
                                                <option value="nv">NV</option>
                                            </template>
                                        </optgroup>
                                        <template v-if="term.category !== 'numeral' && !singPattern.form">
                                            <optgroup label="Named">
                                                <option value="relative">Relative</option>
                                                <option value="IA">Intensive</option>
                                                <option value="NA">Nominal'ed</option>
                                            </optgroup>
                                            <optgroup label="CCC">
                                                <option value="CLC">CLC(e)</option>
                                                <option value="CvCC">CvCC</option>
                                                <option value="CvCCe">CvCCe</option>
                                                <option value="CvCvC">CvCvC(e)</option>
                                                <option value="CiCiC">CiCiC</option>
                                            </optgroup>
                                            <optgroup label="CCCC">
                                                <option value="CvCCvC">CvCCvC(e)</option>
                                                <option value="maCCvC">maCCvC(e)</option>
                                            </optgroup>
                                            <optgroup label="CCLC">
                                                <option value="CCāC">CCāC</option>
                                                <option value="CCāCe">CCāCe</option>
                                                <option value="CCīC">CCīC(e)</option>
                                                <option value="CCūC">CCūC(e)</option>
                                            </optgroup>
                                            <optgroup label="CCCLC">
                                                <option value="CvCCLC">CvCCLC(e)</option>
                                                <option value="Ca22āC">Ca22āC(e)</option>
                                                <option value="Ca22īC">Ca22īC</option>
                                                <option value="Ca22ūC">Ca22ūC(e)</option>
                                            </optgroup>
                                        </template>
                                    </template>
                                </select>
                                <img src="/img/trash.svg" alt="Delete" v-show="singPatterns.length > 0"
                                     @click="removeSingPattern(index, singPatterns)"/>
                            </div>
                        </template>
                        <div class="field-add" v-show="singPatterns.length < 1" @click="addSingPattern()">+ Add SING
                            PATTERN
                        </div>
                    </div>

                    <div v-if="hasPlural(term.category)" class="field-wrapper with-add-field">
                        <div v-for="(plurPattern, index) in plurPatterns" :key="index" class="form-field inline">
                            <label :for="'plurPatterns['+index+'][pattern]'">Plural *</label>
                            <select :id="'plurPatterns['+index+'][pattern]'"
                                    v-model="plurPattern.pattern"
                                    :name="'plurPatterns['+index+'][pattern]'" required>
                                <optgroup label="sound">
                                    <option value="-īn">-īn</option>
                                    <option value="-āt">-āt</option>
                                </optgroup>
                                <optgroup label="CCC">
                                    <option value="CCāC">CCāC</option>
                                    <option value="CCūC">CCūC</option>
                                    <option value="ʔaCCāC">ʔaCCāC</option>
                                    <option value="CvCaC">CvCaC</option>
                                </optgroup>
                                <optgroup label="CCLC">
                                    <option value="ʔaCCiCe">ʔaCCiCe</option>
                                    <option value="CCīC">CCīC</option>
                                    <option value="CuCaCa">CuCaCa</option>
                                    <option value="CuCuC">CuCuC</option>
                                </optgroup>
                                <optgroup label="CCCC">
                                    <option value="CaCāCiC">CaCāCiC</option>
                                </optgroup>
                                <optgroup label="CCCLC">
                                    <option value="CaCāCCe">CaCāCCe</option>
                                    <option value="CaCāCīC">CaCāCīC</option>
                                </optgroup>
                                <optgroup label="CLCC">
                                    <option value="Cu22āC">Cu22āC</option>
                                    <option value="CCāC">CCāC</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="CvCCān">CvCCān</option>
                                </optgroup>
                            </select>
                            <img src="/img/trash.svg" alt="Delete" v-show="plurPatterns.length > 0"
                                 @click="removePlurPattern(index, plurPatterns)"/>
                        </div>
                        <div class="field-add" @click="addPlurPattern()">+ Add PLUR
                            PATTERN
                        </div>
                    </div>
                </div>
                <div class="field-wrapper">
                    <div class="field-label">
                        <div>variants</div>
                    </div>
                    <div class="field-wrapper with-add-field">
                        <div v-for="(spelling, index) in spellings" :key="index" class="form-field inline">
                            <label :for="'spellings['+index+'][spelling]'">Spelling</label>
                            <input :id="'spellings['+index+'][spelling]'" v-model="spelling.spelling"
                                   :name="'spellings['+index+'][spelling]'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="spellings.length > 0"
                                 @click="removeSpelling(index)"/>
                        </div>
                        <div class="field-add" @click="addSpelling()">+ Add SPELLING</div>
                    </div>
                    <div class="field-wrapper with-add-field">
                        <div v-for="(value, index) in variants" :key="index" class="form-field inline">
                            <label :for="'variants['+index+']'">variant</label>
                            <input :id="'variants['+index+']'" v-model="variants[index]"
                                   :name="'variants['+index+']'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="variants.length > 0"
                                 @click="removeItem('variants', index)"/>
                        </div>
                        <div class="field-add" @click="addItem('variants')">+ Add VARIANT</div>
                    </div>
                </div>

                <div class="field-wrapper">
                    <div class="field-label">
                        <div>see also</div>
                    </div>
                    <div class="field-wrapper with-add-field">
                        <div v-for="(value, index) in references" :key="index" class="form-field inline">
                            <label :for="'references['+index+']'">reference</label>
                            <input :id="'references['+index+']'" v-model="references[index]"
                                   :name="'references['+index+']'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="references.length > 0"
                                 @click="removeItem('references', index)"/>
                        </div>
                        <div class="field-add" @click="addItem('references')">+ Add REFERENCE</div>
                    </div>
                </div>

                <div class="field-wrapper">
                    <div class="field-label">
                        <div>usage notes</div>
                    </div>
                    <div class="form-field">
                        <label for="term[usage]">Usage Notes</label>
                        <textarea id="term[usage]" v-model="term.usage"
                                  name="term[usage]"/>
                    </div>
                </div>
            </div>
            <div>
                <div class="field-wrapper with-add-object">
                    <div v-for="(gloss, index) in glosses" :key="index" class="field-wrapper compound-field">
                        <div class="field-label">
                            <div>gloss</div>
                            <img src="/img/trash.svg" alt="Delete" v-show="glosses.length > 1"
                                 @click="removeGloss(gloss)"/>
                        </div>

                        <div class="form-field">
                            <label :for="'glosses['+index+'][gloss]'">Gloss *</label>
                            <input :id="'glosses['+index+'][gloss]'" v-model="gloss.gloss"
                                   :name="'glosses['+index+'][gloss]'"
                                   required type="text"/>
                        </div>

                        <template v-if="term.category === 'verb'">
                            <div class="form-field inline">
                                <label :for="'glosses['+index+'][attribute]'">Attribute *</label>
                                <select :id="'glosses['+index+'][attribute]'" v-model="gloss.attribute"
                                        :name="'glosses['+index+'][attribute]'" required>
                                    <option value="auxiliary">auxiliary</option>
                                    <option label="isPatient">isPatient</option>
                                    <option label="noPatient">noPatient</option>
                                    <option label="hasObject">hasObject</option>
                                </select>
                                <template v-if="gloss.attribute !== '' && gloss.attribute !== 'auxiliary'">
                                    <label :for="'glosses['+index+'][structure]'">Structure *</label>
                                    <select :id="'glosses['+index+'][structure]'" v-model="gloss.structure"
                                            :name="'glosses['+index+'][structure]'" required>
                                        <optgroup label="isPatient" v-if="gloss.attribute === 'isPatient'">
                                            <option value="unaccusative">unaccusative</option>
                                            <option value="passive">passive</option>
                                            <option value="reflexive">reflexive</option>
                                            <option value="reciprocal">reciprocal</option>
                                        </optgroup>
                                        <optgroup label="noPatient" v-if="gloss.attribute === 'noPatient'">
                                            <option value="unergative">unergative</option>
                                            <option value="copular">copular</option>
                                            <option value="stative">stative</option>
                                        </optgroup>
                                        <optgroup label="hasObject" v-if="gloss.attribute === 'hasObject'">
                                            <option value="transitive">transitive</option>
                                            <option value="ditransitive">ditransitive</option>
                                            <option value="causative">causative</option>
                                            <option value="dative">dative</option>
                                        </optgroup>
                                    </select>
                                </template>
                            </div>
                        </template>

                        <div class="field-wrapper with-add-field">
                            <div v-for="(value, index) in gloss.synonyms" :key="index" class="form-field inline">
                                <label :for="'synonyms['+index+']'">synonyms</label>
                                <input :id="'synonyms['+index+']'" v-model="gloss.synonyms[index]"
                                       :name="'synonyms['+index+']'" type="text"/>
                                <img src="/img/trash.svg" alt="Delete" v-show="gloss.synonyms.length > 0"
                                     @click="removeSynonym(index, gloss.synonyms)"/>
                            </div>
                            <div class="field-add" @click="addSynonym(gloss)">+ Add SYNONYM</div>
                        </div>

                        <div class="field-wrapper with-add-field">
                            <div v-for="(value, index) in gloss.antonyms" :key="index" class="form-field inline">
                                <label :for="'antonyms['+index+']'">antonyms</label>
                                <input :id="'antonyms['+index+']'" v-model="gloss.antonyms[index]"
                                       :name="'antonyms['+index+']'" type="text"/>
                                <img src="/img/trash.svg" alt="Delete" v-show="gloss.antonyms.length > 0"
                                     @click="removeAntonym(index, gloss.antonyms)"/>
                            </div>
                            <div class="field-add" @click="addAntonym(gloss)">+ Add ANTONYM</div>
                        </div>

                        <div v-if="term.category === 'verb'" class="field-wrapper with-add-field">
                            <div v-for="(valence, index) in gloss.valences" :key="index" class="form-field inline">
                                <label :for="'valences['+index+'][translit]'">valence</label>
                                <input :id="'valences['+index+'][translit]'" v-model="valence.translit"
                                       :name="'valences['+index+'][translit]'" type="text"/>
                                <select :id="'valences['+index+'][valence]'"
                                        v-model="valence.valence" :name="'valences['+index+'][valence]'" required>
                                    <option value="isPatient">isPatient</option>
                                    <option value="noPatient">noPatient</option>
                                    <option value="hasObject">hasObject</option>
                                </select>
                                <img src="/img/trash.svg" alt="Delete" v-show="gloss.valences.length > 0"
                                     @click="removeValence(index, gloss.valence)"/>
                            </div>
                            <div class="field-add" @click="addValence(gloss)">+ Add VALENCE</div>
                        </div>
                    </div>
                    <div class="field-add" @click="addGloss()">+ Add GLOSS</div>
                </div>
            </div>
        </div>

        <button type="submit" class="sp-button">
            <template v-if="mode === 'add'">Create</template>
            <template v-if="mode === 'edit'">Update</template>
        </button>
    </form>
</template>
