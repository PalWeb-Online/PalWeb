<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from 'ziggy-js';
import {computed, onMounted, ref, watch} from "vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import AppButton from "../../../components/AppButton.vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import {useSearchStore} from "../../../stores/SearchStore.js";
import {useForm} from "@inertiajs/vue3";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";

const props = defineProps({
    term: Object,
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const term = useForm({
    id: props.term?.data.id || null,
    term: props.term?.data.term || '',
    category: props.term?.data.category || '',
    pronunciations: props.term?.data.pronunciations || [],
    root: props.term?.data.root || {
        root: ''
    },
    etymology: props.term?.data.etymology || {
        type: '',
        source: '',
    },
    attributes: props.term?.data.attributes || [],
    spellings: props.term?.data.spellings || [],
    relatives: props.term?.data.relatives || [],
    patterns: props.term?.data.patterns || [],
    glosses: props.term?.data.glosses || [],
    inflections: props.term?.data.inflections || [],
    image: props.term?.data.image || '',
    usage: props.term?.data.usage || '',
});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return term.isDirty && !isSaving.value;
});

const confirmationMessage = computed(() => {
    const messages = [];

    if (term.category === 'verb') {
        if (!term.attributes.some(attribute => attribute.attribute === 'idiom') && !term.patterns.find(pattern => pattern.type === 'verbal')) {
            messages.push('The Verb has no pattern.');
        }

        if (term.glosses.some(gloss => !gloss.attributes.length)) {
            messages.push('The Verb has a Gloss with no Attribute.');
        }
    }

    if (term.category === 'noun' && !term.attributes.some(attribute => ['masculine', 'feminine', 'plural'].includes(attribute.attribute))) {
        messages.push('The Noun has no gender.');
    }

    if (term.category === 'adjective' && !term.inflections.length) {
        messages.push('The Adjective has no Inflections.');
    }

    if (term.attributes.some(attribute => attribute.attribute === 'idiom') && !term.relatives.filter(relative => relative.type === 'component').length) {
        messages.push('The Idiom has no components.');
    }

    return messages.length ? messages.join(' ') : false;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertRelative = (relative) => {
    term.relatives.push({
        slug: relative.slug,
        type: '',
    });
};

const addPronunciation = () => {
    term.pronunciations.push({
        translit: '',
        phonemic: '//',
        phonetic: '[]',
        dialect_id: null,
        dialect: {
            id: null,
            name: ''
        },
        borrowed: 0,
    });
};

const addAttribute = (model, index = null) => {
    if (model === 'term') {
        term.attributes.push({
            attribute: '',
        });

    } else if (model === 'gloss') {
        term.glosses[index].attributes.push({
            attribute: '',
        });
    }
};

const addSpelling = () => {
    term.spellings.push({
        spelling: '',
    })
};

const addPattern = () => {
    term.patterns.push({
        type: '',
        form: '',
        pattern: '',
    });
};

const addInflection = () => {
    term.inflections.push({
        inflection: '',
        translit: '',
        form: '',
    });
};

const addGloss = () => {
    term.glosses.push({
        gloss: '',
        attributes: [],
        relatives: [],
    });

    term.category === 'verb' && addAttribute('gloss', term.glosses.length - 1);
};

const removeItem = (index, fieldType) => {
    fieldType.splice(index, 1);
};

const saveTerm = async () => {
    isSaving.value = true;

    const method = term.id
        ? term.patch.bind(term)
        : term.post.bind(term);

    const url = term.id
        ? route('terms.update', term.id)
        : route('terms.store');

    method(url, {
        onSuccess: () => {
            NotificationStore.addNotification('The Term has been saved!');
            term.defaults();
            isSaving.value = false;
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Term could not be saved.');
            isSaving.value = false;
        },
    });
}

onMounted(() => {
    if (!term.pronunciations.length) {
        addPronunciation();
        term.defaults();
    }

    if (!term.glosses.length) {
        addGloss();
        term.defaults();
    }

    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                insertRelative(newModel);
                SearchStore.deselectModel();
            }
        }
    );
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Dictionary: Build Term"/>
    <div id="app-head">
        <Link :href="route('terms.index')"><h1>Dictionary</h1></Link>
    </div>
    <div id="app-body">
        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <AppButton :disabled="!hasNavigationGuard" label="Save"
                           @click="saveTerm"
                />
                <AppButton :disabled="!hasNavigationGuard" label="Reset"
                           @click="term.reset()"
                />
            </div>
        </div>

        <AppTip v-if="confirmationMessage">
            <p>{{ confirmationMessage }}</p>
        </AppTip>

        <div class="term-editor">
            <div>
                <div class="field-block">
                    <div class="field-block-head">
                        <div>term</div>
                    </div>
                    <div class="field-block-body">
                        <div class="field-item">
                            <label>Term *</label>
                            <input v-model="term.term" required/>
                            <div v-if="term.errors[`term`]" class="field-error">{{
                                    term.errors[`term`]
                                }}
                            </div>
                        </div>
                        <div class="field-block">
                            <div class="field-block-head" @click="addSpelling()">
                                <div>spellings</div>
                                <div class="field-item-add">+</div>
                            </div>
                            <div class="field-block-body" v-if="term.spellings.length > 0">
                                <div class="field-set" v-for="(spelling, index) in term.spellings" :key="index">
                                    <img src="/img/trash.svg" alt="Delete" v-show="term.spellings.length > 0"
                                         @click="removeItem(index, term.spellings)"/>
                                    <div class="field-item">
                                        <input v-model="spelling.spelling"/>
                                        <div v-if="term.errors[`spellings.${index}.spelling`]" class="field-error">
                                            {{ term.errors[`spellings.${index}.spelling`] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Category *</label>
                            <select v-model="term.category" required>
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
                            <div v-if="term.errors[`category`]" class="field-error">{{
                                    term.errors[`category`]
                                }}
                            </div>
                        </div>
                        <div class="field-block">
                            <div class="field-block-head" @click="addAttribute('term')">
                                <div>attributes</div>
                                <div class="field-item-add">+</div>
                            </div>
                            <div class="field-block-body" v-if="term.attributes.length > 0">
                                <div class="field-set"
                                     v-for="(attribute, index) in term.attributes" :key="attribute.id">
                                    <img src="/img/trash.svg" alt="Delete" v-show="term.attributes.length > 0"
                                         @click="removeItem(index, term.attributes)"/>
                                    <div class="field-item">
                                        <select v-model="attribute.attribute">
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
                                        <div v-if="term.errors[`attributes.${index}.attribute`]"
                                             class="field-error">
                                            {{ term.errors[`attributes.${index}.attribute`] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-block">
                    <div class="field-block-head" @click="addPronunciation()">
                        <div>pronunciations</div>
                        <div class="field-item-add">+</div>
                    </div>
                    <div class="field-block-body" v-if="term.pronunciations.length > 0">
                        <div v-for="(pronunciation, index) in term.pronunciations" :key="index"
                             class="field-set">
                            <img src="/img/trash.svg" alt="Delete" v-show="term.pronunciations.length > 1"
                                 @click="removeItem(index, term.pronunciations)"/>
                            <div class="field-item">
                                <label>Translit *</label>
                                <input v-model="pronunciation.translit" required/>
                                <div v-if="term.errors[`pronunciations.${index}.translit`]" class="field-error">
                                    {{ term.errors[`pronunciations.${index}.translit`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Phonemic *</label>
                                <input v-model="pronunciation.phonemic" required/>
                                <div v-if="term.errors[`pronunciations.${index}.phonemic`]" class="field-error">
                                    {{ term.errors[`pronunciations.${index}.phonemic`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Phonetic *</label>
                                <input v-model="pronunciation.phonetic" required/>
                                <div v-if="term.errors[`pronunciations.${index}.phonetic`]" class="field-error">
                                    {{ term.errors[`pronunciations.${index}.phonetic`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Dialect *</label>
                                <select v-model="pronunciation.dialect_id" required>
                                    <option :value="1">General Palestinian</option>
                                    <option :value="2">Urban Palestinian</option>
                                    <option :value="3">Rural Palestinian</option>
                                    <option :value="4">Central Palestinian</option>
                                    <option :value="5">Northern Palestinian</option>
                                    <option :value="6">Palestinian Bedouin</option>
                                    <option :value="7">Palestinian Druze</option>
                                    <option :value="8">Central Urban Palestinian</option>
                                    <option :value="9">Northern Urban Palestinian</option>
                                    <option :value="10">Central Rural Palestinian</option>
                                    <option :value="11">Northern Rural Palestinian</option>
                                    <option :value="12">Southern Urban Palestinian</option>
                                </select>
                                <div v-if="term.errors[`pronunciations.${index}.dialect_id`]" class="field-error">
                                    {{ term.errors[`pronunciations.${index}.dialect_id`] }}
                                </div>
                            </div>
                            <label class="checkbox">
                                <span>Borrowed</span>
                                <input v-model="pronunciation.borrowed" type="checkbox" value=1/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="field-block">
                    <div class="field-block-head">
                        <div>etymology</div>
                    </div>
                    <div class="field-block-body"
                         v-if="!term.attributes.map(attr => attr.attribute).includes(['idiom', 'clitic'])">
                        <div class="field-item">
                            <label>Root{{
                                    term.category === 'verb' && !term.attributes.map(attr => attr.attribute).includes('idiom') ? ' *' : ''
                                }}</label>
                            <input v-model="term.root.root"
                                   :required="term.category === 'verb' && !term.attributes.map(attr => attr.attribute).includes('pseudo')"/>
                            <div v-if="term.errors[`root.root`]" class="field-error">
                                {{ term.errors[`root.root`] }}
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Type *</label>
                            <select v-model="term.etymology.type" required>
                                <option value="inherited">inherited</option>
                                <option value="hybrid">hybrid</option>
                                <option value="borrowed">borrowed</option>
                            </select>
                            <div v-if="term.errors[`etymology.type`]" class="field-error">{{
                                    term.errors[`etymology.type`]
                                }}
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Source</label>
                            <input v-model="term.etymology.source"/>
                        </div>

                        <div class="field-block"
                             v-if="!term.attributes.some(attribute => attribute.attribute === 'idiom')">
                            <div class="field-block-head" @click="addPattern()">
                                <div>patterns</div>
                                <div class="field-item-add">+</div>
                            </div>
                            <div class="field-block-body" v-if="term.patterns.length > 0">
                                <div class="field-set" v-for="(pattern, index) in term.patterns" :key="index">
                                    <img src="/img/trash.svg" alt="Delete"
                                         v-show="term.category !== 'verb' && term.patterns.length > 0 || term.patterns.length > 1"
                                         @click="removeItem(index, term.patterns)"/>
                                    <div class="field-item">
                                        <select v-model="pattern.type">
                                            <option value="verbal">verbal</option>
                                            <option value="singular">singular</option>
                                            <option value="plural">plural</option>
                                        </select>
                                        <template v-if="pattern.type === 'verbal'">
                                            <select v-model="pattern.form" required>
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
                                            <select v-if="pattern.form" v-model="pattern.pattern" required>
                                                <template v-if="pattern.form === '1'">
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
                                                <template v-if="pattern.form !== '1'">
                                                    <option value="A">A</option>
                                                </template>
                                                <template v-if="pattern.form !== '1' && pattern.form !== '9'">
                                                    <option value="B">B</option>
                                                </template>
                                                <template
                                                    v-if="pattern.form === '4' || pattern.form === '7' || pattern.form === '8' || pattern.form === 'X'">
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                </template>
                                                <template v-if="pattern.form === '8'">
                                                    <option value="E">E</option>
                                                </template>
                                                <template v-if="pattern.form === '7'">
                                                    <option value="istanna">istanna</option>
                                                </template>
                                            </select>
                                        </template>
                                        <template v-if="pattern.type === 'singular'">
                                            <select v-model="pattern.form">
                                                <option selected value=""></option>
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
                                            <select v-model="pattern.pattern">
                                                <optgroup v-if="pattern.form" label="Derived Terms">
                                                    <option value="ap">AP</option>
                                                    <option value="pp">PP</option>
                                                    <option value="vn">VN</option>
                                                </optgroup>
                                                <template v-else>
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
                                        </template>
                                        <template v-if="pattern.type === 'plural'">
                                            <select v-model="pattern.pattern" required>
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
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field-block">
                    <div class="field-block-head" @click="addInflection()">
                        <div>inflections</div>
                        <div class="field-item-add">+</div>
                    </div>
                    <div class="field-block-body" v-if="term.inflections.length > 0">
                        <div v-for="(inflection, index) in term.inflections" :key="index"
                             class="field-set">
                            <img src="/img/trash.svg" alt="Delete" v-show="term.inflections.length > 0"
                                 @click="removeItem(index, term.inflections)"/>
                            <div class="field-item">
                                <label>Form *</label>
                                <select v-model="inflection.form" required>
                                    <template v-if="term.category === 'noun'">
                                        <option
                                            v-if="term.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="sing">singulative
                                        </option>
                                        <option
                                            v-if="term.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="pauc">paucal
                                        </option>
                                        <option
                                            v-if="!term.attributes.map(attr => attr.attribute).includes('collective')"
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
                                    <option
                                        v-if="term.category === 'adjective' && term.attributes.length === 0"
                                        value="elt">
                                        elative
                                    </option>
                                    <option value="genitive">host (genitive)</option>
                                    <option value="accusative">host (accusative)</option>
                                    <option v-if="term.category === 'phrase'" value="resp">response</option>
                                </select>
                                <div v-if="term.errors[`inflections.${index}.form`]" class="field-error">
                                    {{ term.errors[`inflections.${index}.form`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Inflection *</label>
                                <input v-model="inflection.inflection" required/>
                                <div v-if="term.errors[`inflections.${index}.inflection`]" class="field-error">
                                    {{ term.errors[`inflections.${index}.inflection`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Translit *</label>
                                <input v-model="inflection.translit" required/>
                                <div v-if="term.errors[`inflections.${index}.translit`]" class="field-error">
                                    {{ term.errors[`inflections.${index}.translit`] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field-block">
                    <div class="field-block-head">
                        <div>Info</div>
                    </div>
                    <div class="field-block-body">
                        <div class="field-block">
                            <div class="field-block-head" @click="SearchStore.openSearchGenie('insert', 'terms')">
                                <div>relatives</div>
                                <div class="field-item-add">+</div>
                            </div>
                            <div class="field-block-body" v-if="term.relatives.length > 0">
                                <div class="field-set" v-for="(relative, index) in term.relatives" :key="index">
                                    <img src="/img/trash.svg" alt="Delete" v-show="term.relatives.length > 0"
                                         @click="removeItem(index, term.relatives)"/>
                                    <div class="field-item">
                                        <input :placeholder="relative.slug" disabled/>
                                        <select v-model="relative.type">
                                            <optgroup label="Term Relative">
                                                <option value="variant">variant</option>
                                                <option value="reference">reference</option>
                                                <option value="component">component</option>
                                                <option value="descendant">descendant</option>
                                            </optgroup>
                                            <optgroup label="Derivative">
                                                <option value="source">source</option>
                                                <option value="ap">AP</option>
                                                <option value="pp">PP</option>
                                                <option value="vn">VN</option>
                                            </optgroup>
                                            <optgroup label="Gloss Relative">
                                                <option value="synonym">synonym</option>
                                                <option value="antonym">antonym</option>
                                                <option value="isPatient">isPatient</option>
                                                <option value="noPatient">noPatient</option>
                                                <option value="hasObject">hasObject</option>
                                            </optgroup>
                                        </select>
                                        <div v-if="term.errors[`relatives.${index}.type`]" class="field-error">
                                            {{ term.errors[`relatives.${index}.type`] }}
                                        </div>

                                        <template
                                            v-if="['synonym', 'antonym', 'isPatient', 'noPatient', 'hasObject'].includes(relative.type)">
                                            <select v-model="relative.gloss_id">
                                                <option value=""></option>
                                                <option v-for="(gloss, index) in term.glosses.filter(gloss => gloss.id)"
                                                        :key="index" :value="gloss.id">
                                                    {{ gloss.gloss }}
                                                </option>
                                            </select>
                                            <div v-if="term.errors[`relatives.${index}.type`]" class="field-error">
                                                {{ term.errors[`relatives.${index}.type`] }}
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Image URL</label>
                            <input v-model="term.image"/>
                        </div>
                        <div class="field-item">
                            <label>Usage Notes</label>
                            <textarea v-model="term.usage"/>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="field-block">
                    <div class="field-block-head" @click="addGloss()">
                        <div>glosses</div>
                        <div class="field-item-add">+</div>
                    </div>
                    <div class="field-block-body" v-if="term.glosses.length > 0">
                        <div v-for="(gloss, index) in term.glosses" :key="index"
                             class="field-set">
                            <img src="/img/trash.svg" alt="Delete" v-show="term.glosses.length > 1"
                                 @click="removeItem(index, term.glosses)"/>

                            <div class="field-item">
                                <label>Gloss *</label>
                                <input v-model="gloss.gloss" required/>
                                <div v-if="term.errors[`glosses.${index}.gloss`]" class="field-error">
                                    {{ term.errors[`glosses.${index}.gloss`] }}
                                </div>
                            </div>

                            <div class="field-block">
                                <div class="field-block-head" @click="addAttribute('gloss', index)">
                                    <div>attributes</div>
                                    <div class="field-item-add">+</div>
                                </div>
                                <div class="field-block-body" v-if="gloss.attributes.length > 0">
                                    <div class="field-set"
                                         v-for="(attribute, i) in gloss.attributes" :key="i">
                                        <img src="/img/trash.svg" alt="Delete" v-show="gloss.attributes.length > 1 || (term.category !== 'verb' && gloss.attributes.length > 0)"
                                             @click="removeItem(i, gloss.attributes)"/>
                                        <div class="field-item">
                                            <select v-model="attribute.attribute">
                                                <option value="auxiliary">auxiliary</option>
                                                <option value="participle" v-if="term.category === 'adjective'">
                                                    participle
                                                </option>
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
                                            <div v-if="term.errors[`glosses.${index}.attributes.${i}.attribute`]"
                                                 class="field-error">
                                                {{ term.errors[`glosses.${index}.attributes.${i}.attribute`] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
