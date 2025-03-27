<script setup>
import Layout from "../../../Shared/Layout.vue";
import {cloneDeep, isEqual, merge} from "lodash";
import {route} from 'ziggy-js';
import {computed, onMounted, reactive, ref, watch} from "vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import AppButton from "../../../components/AppButton.vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import AppAlert from "../../../components/AppAlert.vue";
import {useSearchStore} from "../../../stores/SearchStore.js";
import {router} from "@inertiajs/vue3";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";

const props = defineProps({
    term: Object,
    errors: Object
});

defineOptions({
    layout: Layout
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const init = {
    id: null,
    term: '',
    category: '',
    pronunciations: [],
    root: {
        root: ''
    },
    etymology: {
        type: '',
        source: '',
    },
    attributes: [],
    spellings: [],
    relatives: [],
    patterns: [],
    glosses: [],
    inflections: [],
    image: '',
    usage: '',
};

const stagedTerm = reactive(merge(init, props.term?.data || {}));
let originalTerm = reactive({});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return !isEqual(stagedTerm, originalTerm);
});

onMounted(() => {
    if (!stagedTerm.pronunciations.length) addPronunciation();
    if (!stagedTerm.glosses.length) addGloss();
    Object.assign(originalTerm, cloneDeep(stagedTerm));

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

const insertRelative = (relative) => {
    console.log(relative);

    stagedTerm.relatives.push({
        slug: relative.slug,
        type: '',
    });
};

const addPronunciation = () => {
    stagedTerm.pronunciations.push({
        translit: '',
        phonemic: '//',
        phonetic: '[]',
        dialect: {
            id: null,
            name: ''
        },
        borrowed: 0,
    });
};

const addAttribute = (model, index = null) => {
    if (model === 'term') {
        stagedTerm.attributes.push({
            attribute: '',
        });

    } else if (model === 'gloss') {
        stagedTerm.glosses[index].attributes.push({
            attribute: '',
        });
    }
};

const addSpelling = () => {
    stagedTerm.spellings.push({
        spelling: '',
    })
};

const addPattern = () => {
    stagedTerm.patterns.push({
        type: '',
        form: '',
        pattern: '',
    });
};

const addInflection = () => {
    stagedTerm.inflections.push({
        inflection: '',
        translit: '',
        form: '',
    });
};

const addGloss = () => {
    stagedTerm.glosses.push({
        gloss: '',
        attributes: [],
        relatives: [],
    });
};

const removeItem = (index, fieldType) => {
    fieldType.splice(index, 1);
};

const saveTerm = async () => {
    isSaving.value = true;

    const method = stagedTerm.id
        ? router.patch.bind(router)
        : router.post.bind(router);

    const url = stagedTerm.id
        ? route('terms.update', stagedTerm.id)
        : route('terms.store');

    method(url, {term: stagedTerm},
        {
            onSuccess: () => {
                NotificationStore.addNotification('The Term has been saved!');
                // Object.assign(originalTerm, cloneDeep(stagedTerm));
                isSaving.value = false;
            },
            onError: () => {
                NotificationStore.addNotification('Oh no! The Term could not be saved.');
                isSaving.value = false;
            },
        }
    );
}

const resetTerm = async () => {
    Object.assign(stagedTerm, cloneDeep(originalTerm));
};

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);
</script>

<template>
    <Head title="Dictionary: Build Term"/>
    <div id="app-head">
        <h1>Dictionary</h1>
    </div>
    <div id="app-body">
        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <AppButton :disabled="!hasNavigationGuard" label="Save"
                           @click="saveTerm"
                />
                <AppButton :disabled="!hasNavigationGuard" label="Reset"
                           @click="resetTerm"
                />
            </div>
        </div>

        <div class="term-editor">
            <div>
                <div class="field-block">
                    <div class="field-block-head">
                        <div>term</div>
                    </div>
                    <div class="field-block-body">
                        <div class="field-item">
                            <label>Term *</label>
                            <input v-model="stagedTerm.term" required/>
                            <div v-if="errors[`term.term`]" class="field-error">{{ errors[`term.term`] }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-block-head">
                                <div>spellings</div>
                                <div class="field-item-add" @click="addSpelling()">+</div>
                            </div>
                            <div class="field-block-body" v-if="stagedTerm.spellings.length > 0">
                                <div class="field-set" v-for="(spelling, index) in stagedTerm.spellings" :key="index">
                                    <img src="/img/trash.svg" alt="Delete" v-show="stagedTerm.spellings.length > 0"
                                         @click="removeItem(index, stagedTerm.spellings)"/>
                                    <div class="field-item">
                                        <input v-model="spelling.spelling"/>
                                        <div v-if="errors[`term.spellings.${index}.spelling`]" class="field-error">
                                            {{ errors[`term.spellings.${index}.spelling`] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Category *</label>
                            <select v-model="stagedTerm.category" required>
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
                            <div v-if="errors[`term.category`]" class="field-error">{{ errors[`term.category`] }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-block-head">
                                <div>attributes</div>
                                <div class="field-item-add" @click="addAttribute('term')">+</div>
                            </div>
                            <div class="field-block-body" v-if="stagedTerm.attributes.length > 0">
                                <div class="field-set"
                                     v-for="(attribute, index) in stagedTerm.attributes" :key="attribute.id">
                                    <img src="/img/trash.svg" alt="Delete" v-show="stagedTerm.attributes.length > 0"
                                         @click="removeItem(index, stagedTerm.attributes)"/>
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
                                        <div v-if="errors[`term.attributes.${index}.attribute`]" class="field-error">
                                            {{ errors[`term.attributes.${index}.attribute`] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-block">
                    <div class="field-block-head">
                        <div>pronunciations</div>
                        <div class="field-item-add" @click="addPronunciation()">+</div>
                    </div>
                    <div class="field-block-body" v-if="stagedTerm.pronunciations.length > 0">
                        <div v-for="(pronunciation, index) in stagedTerm.pronunciations" :key="index"
                             class="field-set">
                            <img src="/img/trash.svg" alt="Delete" v-show="stagedTerm.pronunciations.length > 1"
                                 @click="removeItem(index, stagedTerm.pronunciations)"/>
                            <div class="field-item">
                                <label>Translit *</label>
                                <input v-model="pronunciation.translit" required/>
                                <div v-if="errors[`term.pronunciations.${index}.translit`]" class="field-error">
                                    {{ errors[`term.pronunciations.${index}.translit`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Phonemic *</label>
                                <input v-model="pronunciation.phonemic" required/>
                                <div v-if="errors[`term.pronunciations.${index}.phonemic`]" class="field-error">
                                    {{ errors[`term.pronunciations.${index}.phonemic`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Phonetic *</label>
                                <input v-model="pronunciation.phonetic" required/>
                                <div v-if="errors[`term.pronunciations.${index}.phonetic`]" class="field-error">
                                    {{ errors[`term.pronunciations.${index}.phonetic`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Dialect *</label>
                                <select v-model="pronunciation.dialect.id" required>
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
                                <div v-if="errors[`term.pronunciations.${index}.dialect.id`]" class="field-error">
                                    {{ errors[`term.pronunciations.${index}.dialect.id`] }}
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
                         v-if="!stagedTerm.attributes.map(attr => attr.attribute).includes(['idiom', 'clitic'])">
                        <div class="field-item">
                            <label>Root{{
                                    stagedTerm.category === 'verb' && !stagedTerm.attributes.map(attr => attr.attribute).includes('idiom') ? ' *' : ''
                                }}</label>
                            <input v-model="stagedTerm.root.root"
                                   :required="stagedTerm.category === 'verb' && !stagedTerm.attributes.map(attr => attr.attribute).includes('pseudo')"/>
                            <div v-if="errors[`term.root.root`]" class="field-error">{{
                                    errors[`term.term.root`]
                                }}
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Type *</label>
                            <select v-model="stagedTerm.etymology.type" required>
                                <option value="inherited">inherited</option>
                                <option value="hybrid">hybrid</option>
                                <option value="borrowed">borrowed</option>
                            </select>
                            <div v-if="errors[`term.etymology.type`]" class="field-error">{{
                                    errors[`term.etymology.type`]
                                }}
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Source</label>
                            <input v-model="stagedTerm.etymology.source"/>
                        </div>

                        <div class="field-block">
                            <div class="field-block-head">
                                <div>patterns</div>
                                <div class="field-item-add" @click="addPattern()">+</div>
                            </div>
                            <div class="field-block-body" v-if="stagedTerm.patterns.length > 0">
                                <div class="field-set" v-for="(pattern, index) in stagedTerm.patterns" :key="index">
                                    <!--                               todo: I don't think it's guaranteed that if the user doesn't add a verbal pattern, it will fail-->
                                    <img src="/img/trash.svg" alt="Delete"
                                         v-show="(stagedTerm.category !== 'verb' && stagedTerm.patterns.length > 0) || (stagedTerm.patterns.length > 1)"
                                         @click="removeItem(index, stagedTerm.patterns)"/>
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
                                                    <option value="nv">NV</option>
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
                    <div class="field-block-head">
                        <div>inflections</div>
                        <div class="field-item-add" @click="addInflection()">+</div>
                    </div>
                    <div class="field-block-body" v-if="stagedTerm.inflections.length > 0">
                        <div v-for="(inflection, index) in stagedTerm.inflections" :key="index"
                             class="field-set">
                            <img src="/img/trash.svg" alt="Delete" v-show="stagedTerm.inflections.length > 0"
                                 @click="removeItem(index, stagedTerm.inflections)"/>
                            <div class="field-item">
                                <label>Form *</label>
                                <select v-model="inflection.form" required>
                                    <template
                                        v-if="stagedTerm.category === 'verb' && !stagedTerm.attributes.map(attr => attr.attribute).includes('pseudo')">
                                        <option value="ap">AP</option>
                                        <option value="pp">PP</option>
                                        <option value="nv">NV</option>
                                    </template>

                                    <template v-if="stagedTerm.category === 'noun'">
                                        <option
                                            v-if="stagedTerm.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="sing">singulative
                                        </option>
                                        <option
                                            v-if="stagedTerm.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="pauc">paucal
                                        </option>
                                        <option
                                            v-if="!stagedTerm.attributes.map(attr => attr.attribute).includes('collective')"
                                            value="fem">feminine
                                        </option>
                                        <option value="plr">plural</option>
                                    </template>
                                    <option v-if="stagedTerm.category === 'numeral'" value="cnst">construct</option>
                                    <template
                                        v-if="stagedTerm.category === 'adjective' || stagedTerm.category === 'numeral' || stagedTerm.category === 'particle'">
                                        <option value="fem">feminine</option>
                                        <option value="plr">plural</option>
                                    </template>
                                    <option
                                        v-if="stagedTerm.category === 'adjective' && stagedTerm.attributes.length === 0"
                                        value="elt">
                                        elative
                                    </option>
                                    <option value="genitive">host (genitive)</option>
                                    <option value="accusative">host (accusative)</option>
                                    <option v-if="stagedTerm.category === 'phrase'" value="resp">response</option>
                                </select>
                                <div v-if="errors[`term.inflections.${index}.form`]" class="field-error">
                                    {{ errors[`term.inflections.${index}.form`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Inflection *</label>
                                <input v-model="inflection.inflection" required/>
                                <div v-if="errors[`term.inflections.${index}.inflection`]" class="field-error">
                                    {{ errors[`term.inflections.${index}.inflection`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>Translit *</label>
                                <input v-model="inflection.translit" required/>
                                <div v-if="errors[`term.inflections.${index}.translit`]" class="field-error">
                                    {{ errors[`term.inflections.${index}.translit`] }}
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
                            <div class="field-block-head">
                                <div>relatives</div>
                                <div class="field-item-add" @click="SearchStore.openSearchGenie('insert', 'terms')">+</div>
                            </div>
                            <div class="field-block-body" v-if="stagedTerm.relatives.length > 0">
                                <div class="field-set" v-for="(relative, index) in stagedTerm.relatives" :key="index">
                                    <img src="/img/trash.svg" alt="Delete" v-show="stagedTerm.relatives.length > 0"
                                         @click="removeItem(index, stagedTerm.relatives)"/>
                                    <div class="field-item">
                                        <select v-model="relative.type">
                                            <option value="variant">variant</option>
                                            <option value="reference">reference</option>
                                            <option value="component">component</option>
                                            <option value="descendant">descendant</option>
                                        </select>
                                        <div v-if="errors[`term.relatives.${index}.type`]" class="field-error">
                                            {{ errors[`term.relatives.${index}.type`] }}
                                        </div>
                                    </div>
                                    <div class="field-item">
                                        <div>{{ relative.slug }}</div>
                                        <div v-if="errors[`term.relatives.${index}.slug`]" class="field-error">
                                            {{ errors[`term.relatives.${index}.slug`] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field-item">
                            <label>Image URL</label>
                            <input v-model="stagedTerm.image"/>
                        </div>
                        <div class="field-item">
                            <label>Usage Notes</label>
                            <textarea v-model="stagedTerm.usage"/>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="field-block">
                    <div class="field-block-head">
                        <div>glosses</div>
                        <div class="field-item-add" @click="addGloss()">+</div>
                    </div>
                    <div class="field-block-body" v-if="stagedTerm.glosses.length > 0">
                        <div v-for="(gloss, index) in stagedTerm.glosses" :key="index"
                             class="field-set">
                            <img src="/img/trash.svg" alt="Delete" v-show="stagedTerm.glosses.length > 1"
                                 @click="removeItem(index, stagedTerm.glosses)"/>

                            <div class="field-item">
                                <label>Gloss *</label>
                                <input v-model="gloss.gloss" required/>
                                <div v-if="errors[`term.glosses.${index}.gloss`]" class="field-error">
                                    {{ errors[`term.glosses.${index}.gloss`] }}
                                </div>
                            </div>

                            <div class="field-block">
                                <div class="field-block-head">
                                    <div>attributes</div>
                                    <div class="field-item-add" @click="addAttribute('gloss', index)">+</div>
                                </div>
                                <div class="field-block-body" v-if="gloss.attributes.length > 0">
                                    <div class="field-set"
                                         v-for="(attribute, i) in gloss.attributes" :key="i">
                                        <!--                                            todo: automatically add 1 to verbs & disallow deleting-->
                                        <!--                                            <label>attribute{{ term.category === 'verb' ? ' *' : '' }}</label>-->
                                        <img src="/img/trash.svg" alt="Delete" v-show="gloss.attributes.length > 0"
                                             @click="removeItem(i, gloss.attributes)"/>
                                        <div class="field-item">
                                            <select v-model="attribute.attribute">
                                                <option value="auxiliary">auxiliary</option>
                                                <option value="participle" v-if="stagedTerm.category === 'adjective'">
                                                    participle
                                                </option>
                                                <template v-if="stagedTerm.category === 'verb'">
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
                                            <div v-if="errors[`term.glosses.${index}.attributes.${i}.attribute`]"
                                                 class="field-error">
                                                {{ errors[`term.glosses.${index}.attributes.${i}.attribute`] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-block">
                                <div class="field-block-head">
                                    <div>relatives</div>
                                    <div class="field-item-add" @click="addRelative('gloss', index)">+</div>
                                </div>
                                <div class="field-block-body" v-if="gloss.relatives.length > 0">
                                    <div class="field-set"
                                         v-for="(relative, i) in gloss.relatives" :key="i">
                                        <img src="/img/trash.svg" alt="Delete" v-show="gloss.relatives.length > 0"
                                             @click="removeItem(i, gloss.relatives)"/>
                                        <div class="field-item">
                                            <select v-model="relative.type" required>
                                                <option value="synonym">synonym</option>
                                                <option value="antonym">antonym</option>
                                                <optgroup label="valences" v-if="stagedTerm.category === 'verb'">
                                                    <option value="isPatient">isPatient</option>
                                                    <option value="noPatient">noPatient</option>
                                                    <option value="hasObject">hasObject</option>
                                                </optgroup>
                                            </select>
                                            <div v-if="errors[`term.glosses.${index}.relatives.${i}.type`]"
                                                 class="field-error">
                                                {{ errors[`term.glosses.${index}.relatives.${i}.type`] }}
                                            </div>
                                        </div>
                                        <div class="field-item">
                                            <input v-model="relative.slug"/>
                                            <div v-if="errors[`term.glosses.${index}.relatives.${i}.slug`]"
                                                 class="field-error">
                                                {{ errors[`term.glosses.${index}.relatives.${i}.slug`] }}
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

        <AppAlert
            v-if="showAlert"
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </div>
</template>
