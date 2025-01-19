<script>
export default {
    props: [
        'mode',
        'termId'
    ],

    data() {
        return {
            term: {
                term: '',
                category: '',
                attributes: [],
                etymology: {
                    type: '',
                    source: '',
                },
                image: '',
                usage: '',
            },
            root: '',
            singPatterns: [],
            plurPatterns: [],
            verbPatterns: [],
            pronunciations: [],
            variants: [],
            references: [],
            components: [],
            descendants: [],
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
        if (this.mode == 'edit') {
            this.getTerm(this.termId);
        }
    },
    methods: {
        getTerm(id) {
            axios.get("/dictionary/terms/" + id + "/get")
                .then(response => {
                    this.term = response.data.term;
                    this.root = (response.data.root) ? response.data.root : this.root;
                    this.singPatterns = response.data.singPatterns;
                    this.plurPatterns = response.data.plurPatterns;
                    this.verbPatterns = response.data.verbPatterns;
                    this.pronunciations = response.data.pronunciations;
                    this.variants = response.data.variants;
                    this.references = response.data.references;
                    this.components = response.data.components;
                    this.descendants = response.data.descendants;
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

        addAttribute(model, index = null) {
            if (model === 'term') {
                this.term.attributes.push({
                    attribute: '',
                });
            } else if (model === 'gloss') {
                this.glosses[index].attributes.push({
                    attribute: '',
                });
            }
        },
        removeAttribute(index, fieldType) {
            fieldType.splice(index, 1);
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
                attributes: [],
                relatives: [],
            });
        },
        removeGloss(item) {
            this.glosses.splice(this.glosses.indexOf(item), 1);
        },

        addRelative(itemName, relation) {
            this[itemName].push({
                slug: '',
                relation: relation,
            });
        },
        removeRelative(itemName, index) {
            this[itemName].splice(index, 1)
        },

        addGlossRelative(gloss) {
            let item = this.glosses.find(itm => itm == gloss);
            item.relatives.push({
                slug: '',
                relation: '',
            });
        },
        removeGlossRelative(index, fieldType) {
            fieldType.splice(index, 1)
        },

        submit() {
            const requestMethod = this.mode === 'create' ? axios.post : axios.patch;
            const requestRoute = this.mode === 'create' ? `/dictionary/terms` : `/dictionary/terms/${this.termId}`;

            requestMethod(requestRoute, {
                term: this.term,
                root: this.root,
                singPatterns: this.singPatterns,
                plurPatterns: this.plurPatterns,
                verbPatterns: this.verbPatterns,
                pronunciations: this.pronunciations,
                variants: this.variants,
                references: this.references,
                components: this.components,
                descendants: this.descendants,
                spellings: this.spellings,
                inflections: this.inflections,
                glosses: this.glosses
            })
                .then(response => {
                    window.location = response.data.redirect;
                })
                .catch(error => {
                    this.errors = error.response?.data?.message || "An error occurred during the request.";
                });
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
                        <div v-for="(value, index) in term.attributes" :key="index" class="form-field inline">
                            <label :for="'attributes['+index+'][attribute]'">attribute</label>
                            <select :id="'attributes['+index+'][attribute]'" v-model="term.attributes[index].attribute"
                                    :name="'attributes['+index+'][attribute]'">
                                <option value="masculine">masculine</option>
                                <option value="feminine">feminine</option>
                                <option value="plural">plural</option>
                                <option value="collective">collective</option>
                                <option value="demonym">demonym</option>
                                <option value="defect">defect</option>
                                <option value="pseudo">pseudo</option>
                                <option value="clitic">clitic</option>
                                <option value="idiom">idiom</option>
                            </select>
                            <img src="/img/trash.svg" alt="Delete" v-show="term.attributes.length > 0"
                                 @click="removeAttribute(index, term.attributes)"/>
                        </div>
                        <div class="field-add" @click="addAttribute('term')">+ Add ATTRIBUTE</div>
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
                                <option value="12">Southern Urban Palestinian</option>
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
                                <template
                                    v-if="term.category === 'verb' && !term.attributes.map(attr => attr.attribute).includes('pseudo')">
                                    <option value="ap">AP</option>
                                    <option value="pp">PP</option>
                                    <option value="nv">NV</option>
                                </template>

                                <template v-if="term.category === 'noun'">
                                    <option v-if="term.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="sing">singulative
                                    </option>
                                    <option v-if="term.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="pauc">paucal
                                    </option>
                                    <option v-if="!term.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="fem">feminine
                                    </option>
                                    <option value="plr">plural</option>
                                </template>
                                <option v-if="term.category === 'numeral'" value="cnst">construct</option>
                                <template
                                    v-if="term.category === 'adjective' || term.category === 'numeral' || term.category === 'particle'">
                                    <option value="fem">feminine</option>
                                    <option value="plr">plural</option>
                                </template>
                                <option v-if="term.category === 'adjective' && term.attributes.length === 0"
                                        value="elt">
                                    elative
                                </option>
                                <option value="genitive">host (genitive)</option>
                                <option value="accusative">host (accusative)</option>
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

                    <div v-if="term.attributes.map(attr => attr.attribute).includes('idiom')"
                         class="field-wrapper with-add-field">
                        <div v-for="(value, index) in components" :key="index" class="form-field inline">
                            <label :for="'components['+index+'][slug]'">component</label>
                            <input :id="'components['+index+'][slug]'" v-model="components[index].slug"
                                   :name="'components['+index+'][slug]'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="components.length > 0"
                                 @click="removeRelative('components', index)"/>
                        </div>
                        <div class="field-add" @click="addRelative('components', 'component')">+ Add COMPONENT</div>
                    </div>

                    <template v-if="!term.attributes.map(attr => attr.attribute).includes(['idiom', 'clitic'])">
                        <div class="form-field">
                            <label for="root">Root{{
                                    term.category === 'verb' && !term.attributes.map(attr => attr.attribute).includes('idiom') ? ' *' : ''
                                }}</label>
                            <input id="root" v-model="root"
                                   :required="term.category === 'verb' && !term.attributes.map(attr => attr.attribute).includes('pseudo')"
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
                                        <option value="A1h">A1h</option>
                                        <option value="A2">A2</option>
                                        <option value="A2i">A2i</option>
                                        <option value="B1">B1</option>
                                        <option value="B1a">B1a</option>
                                        <option value="B2">B2</option>
                                        <option value="B2i">B2i</option>
                                        <option value="Ci">Ci</option>
                                        <option value="Ca">Ca</option>
                                        <option value="Cu">Cu</option>
                                        <option value="DY">DY</option>
                                        <option value="DAi">DAi</option>
                                        <option value="DAu">DAu</option>
                                        <option value="DW">DW</option>
                                        <option value="ʔaža">ʔaža</option>
                                    </template>
                                    <template v-if="verbPattern.form !== '1'">
                                        <option value="A">A</option>
                                    </template>
                                    <template v-if="verbPattern.form !== '1' && verbPattern.form !== '9'">
                                        <option value="B">B</option>
                                    </template>
                                    <template
                                        v-if="verbPattern.form === '4' || verbPattern.form === '7' || verbPattern.form === '8' || verbPattern.form === 'X'">
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </template>
                                    <template v-if="verbPattern.form === '8'">
                                        <option value="E">E</option>
                                    </template>
                                    <template v-if="verbPattern.form === '7'">
                                        <option value="istanna">istanna</option>
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
                                        name="singPatterns[pattern]">
                                    <optgroup v-if="singPattern.form" label="Derived Terms">
                                        <option value="ap">AP</option>
                                        <template
                                            v-if="term.category !== 'numeral' && !term.attributes.map(attr => attr.attribute).includes('participle')">
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
                            <label :for="'variants['+index+'][slug]'">variant</label>
                            <input :id="'variants['+index+'][slug]'" v-model="variants[index].slug"
                                   :name="'variants['+index+'][slug]'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="variants.length > 0"
                                 @click="removeRelative('variants', index)"/>
                        </div>
                        <div class="field-add" @click="addRelative('variants', 'variant')">+ Add VARIANT</div>
                    </div>
                </div>

                <div class="field-wrapper">
                    <div class="field-label">
                        <div>see also</div>
                    </div>
                    <div class="field-wrapper with-add-field">
                        <div v-for="(value, index) in references" :key="index" class="form-field inline">
                            <label :for="'references['+index+'][slug]'">reference</label>
                            <input :id="'references['+index+'][slug]'" v-model="references[index].slug"
                                   :name="'references['+index+'][slug]'" type="text"/>
                            <img src="/img/trash.svg" alt="Delete" v-show="references.length > 0"
                                 @click="removeRelative('references', index)"/>
                        </div>
                        <div class="field-add" @click="addRelative('references', 'reference')">+ Add REFERENCE</div>
                    </div>
                    <div class="field-wrapper with-add-field">
                        <div v-for="(value, index) in descendants" :key="index" class="form-field inline">
                            <label :for="'descendants['+index+'][slug]'">descendant</label>
                            <input :id="'descendants['+index+'][slug]'" v-model="descendants[index].slug"
                                   :name="'descendants['+index+'][slug]'" type="text" readonly/>
                            <img src="/img/trash.svg" alt="Delete" v-show="descendants.length > 0"
                                 @click="removeRelative('descendants', index)"/>
                        </div>
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

                        <!--                        TODO: attach the attribute, rather than write to glosses table -->
                        <div class="field-wrapper with-add-field">
                            <div v-for="(value, i) in gloss.attributes" :key="i" class="form-field inline">
                                <label
                                    :for="'attributes['+i+'][attribute]'">attribute{{
                                        term.category === 'verb' ? ' *' : ''
                                    }}</label>
                                <select :id="'attributes['+i+'][attribute]'"
                                        v-model="gloss.attributes[i].attribute"
                                        :name="'attributes['+i+'][attribute]'">
                                    <option value="auxiliary">auxiliary</option>
                                    <option value="participle" v-if="term.category === 'adjective'">participle</option>

                                    <template v-if="term.category === 'verb'">
                                        <optgroup label="isPatient">
                                            <option value="unaccusative">unaccusative</option>
                                            <option value="mediopassive">mediopassive</option>
                                            <option value="reflexive">reflexive</option>
                                            <option value="reciprocal">reciprocal</option>
                                        </optgroup>
                                        <optgroup label="noPatient">
                                            <option value="unergative">unergative</option>
                                            <option value="copular">copular</option>
                                            <option value="stative">stative</option>
                                        </optgroup>
                                        <optgroup label="hasObject">
                                            <option value="transitive">transitive</option>
                                            <option value="ditransitive">ditransitive</option>
                                            <option value="causative">causative</option>
                                            <option value="dative">dative</option>
                                        </optgroup>
                                    </template>
                                </select>
                                <img src="/img/trash.svg" alt="Delete" v-show="gloss.attributes.length > 0"
                                     @click="removeAttribute(i, gloss.attributes)"/>
                            </div>
                            <div class="field-add" @click="addAttribute('gloss', index)">+ Add ATTRIBUTE
                            </div>
                        </div>

                        <div class="field-wrapper with-add-field">
                            <div v-for="(relative, index) in gloss.relatives" :key="index" class="form-field inline">
                                <label :for="'relatives['+index+'][slug]'">relative</label>
                                <input :id="'relatives['+index+'][slug]'" v-model="relative.slug"
                                       :name="'relatives['+index+'][slug]'" type="text"/>
                                <select :id="'relatives['+index+'][relation]'"
                                        v-model="relative.relation" :name="'relatives['+index+'][relation]'" required>
                                    <option value="synonym">synonym</option>
                                    <option value="antonym">antonym</option>
                                    <optgroup label="valences" v-if="term.category === 'verb'">
                                        <option value="isPatient">isPatient</option>
                                        <option value="noPatient">noPatient</option>
                                        <option value="hasObject">hasObject</option>
                                    </optgroup>
                                </select>
                                <img src="/img/trash.svg" alt="Delete" v-show="gloss.relatives.length > 0"
                                     @click="removeGlossRelative(index, gloss.relatives)"/>
                            </div>
                            <div class="field-add" @click="addGlossRelative(gloss)">+ Add RELATIVE</div>
                        </div>
                    </div>
                    <div class="field-add" @click="addGloss()">+ Add GLOSS</div>
                </div>
            </div>
        </div>

        <button type="submit" class="sp-button">
            <template v-if="mode === 'create'">Create</template>
            <template v-if="mode === 'edit'">Update</template>
        </button>
    </form>
</template>
