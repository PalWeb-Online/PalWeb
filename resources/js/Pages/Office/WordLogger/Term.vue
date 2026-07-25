<script setup>
import Layout from "../../../Shared/Layout.vue";
import {computed, onMounted, watch} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import {useSearchStore} from "../../../stores/SearchStore.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import {useTermEditor} from "../../../composables/terms/useTermEditor.js";
import {useTermValidation} from "../../../composables/terms/useTermValidation.js";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {route} from "ziggy-js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import PinButton from "../../../components/PinButton.vue";
import TermActions from "../../../components/Actions/TermActions.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    termId: {
        type: Number,
        default: null,
    },
    editorData: {
        type: Object,
        default: () => ({
            attributes: [],
            dialects: [],
        }),
    },
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const {
    form,
    errors: backendErrors,
    isDirty,
    reset,
    isSaving,
    isLoadingForm,
    term,
    termsNotFound,
    loadForm,
    reloadForm,
    saveTerm,
    addSpelling,
    addAttribute,
    addPattern,
    addPronunciation,
    addGloss,
    addInflection,
    insertRelative,
    removeItem,
} = useTermEditor({
    termId: computed(() => props.termId),
});

const {
    isValidRequest,
    validationErrors,
    confirmableIssues,
} = useTermValidation({
    form,
    backendErrors,
});

const hasNavigationGuard = computed(() => isDirty.value);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const glossRelativeTypes = ['synonym', 'antonym', 'isPatient', 'noPatient', 'hasObject'];

const handleRelativeTypeChange = (relative) => {
    if (!glossRelativeTypes.includes(relative.type)) {
        delete relative.gloss_id;
        return;
    }

    relative.gloss_id ??= '';
};

onMounted(async () => {
    await loadForm();

    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                console.log(newModel.id, term?.id)
                if (newModel.id === term?.id) {
                    SearchStore.deselectModel();
                    NotificationStore.addNotification('Term cannot be a relative of itself.');
                    return;
                }

                insertRelative(newModel);
                SearchStore.deselectModel();
            }
        }
    );
});

watch(() => props.termId, async () => {
    await reloadForm();
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Word Logger: Build Term"/>
    <div id="app-head">
        <h1>{{ $t('pages.word-logger.title') }}</h1>
    </div>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingForm"/>
        <template v-else-if="termsNotFound">
            <AppTip>
                <p>{{ $t('pages.common.not-found', {model: $t('actions.models.term')}) }}</p>
            </AppTip>
            <Link class="portal-button" :href="route('word-logger.index')">Back to Word Logger</Link>
        </template>

        <template v-else>
            <div class="window-container">
                <div class="window-header">
                    <Link :href="route('word-logger.index')" class="material-symbols-rounded">arrow_back</Link>
                    <div class="window-header-url">www.palweb.app/library/terms/{term}</div>
                    <button class="material-symbols-rounded" @click="saveTerm()"
                            :disabled="isSaving || !hasNavigationGuard || !isValidRequest">
                        save
                    </button>
                    <button class="material-symbols-rounded" @click="reset()"
                            :disabled="isSaving || !hasNavigationGuard">
                        undo
                    </button>
                </div>
                <AppTip>
                    <p>{{
                            $t('forms.messages.current-status', {
                                model: $t('actions.models.term'),
                                status: term?.id ? $t('forms.status.published') : $t('forms.status.draft')
                            })
                        }}</p>
                    <p v-if="Object.keys(validationErrors).length" style="font-weight: 700">
                        {{ $t('forms.messages.has-validation-errors', {model: $t('actions.models.term')}) }}
                        Please review the form inputs.
                    </p>
                    <template v-if="confirmableIssues.length">
                        <p><b>{{ $t('forms.messages.has-confirmable-issues') }}</b></p>
                        <ul>
                            <li v-for="issue in confirmableIssues">{{ issue }}</li>
                        </ul>
                    </template>
                </AppTip>
                <div class="window-section-head">
                    <h1>{{ $t('components.term.title') }}</h1>
                    <PinButton v-if="term?.id" modelType="term" :model="term"/>
                    <TermActions v-if="term?.id" :model="term"/>
                </div>
            </div>

            <div class="term-editor">
                <div>
                    <div class="field-block">
                        <div class="field-block-head">
                            <div>{{ $t('components.term.title') }}</div>
                        </div>
                        <div class="field-block-body">
                            <div class="field-item">
                                <label>{{ $t('components.term.title') }}</label>
                                <input v-model="form.term" required/>
                                <div v-if="validationErrors[`term`]" class="field-error">
                                    {{ validationErrors[`term`] }}
                                </div>
                            </div>
                            <div class="field-block">
                                <div class="field-block-head" @click="addSpelling()">
                                    <div>{{ $t('models.spellings') }}</div>
                                    <div class="field-item-add">+</div>
                                </div>
                                <div class="field-block-body" v-if="form.spellings.length > 0">
                                    <div class="field-set" v-for="(spelling, index) in form.spellings" :key="index">
                                        <img src="/img/trash.svg" alt="Delete" v-show="form.spellings.length > 0"
                                             @click="removeItem(index, form.spellings)"/>
                                        <div class="field-item">
                                            <input v-model="spelling.spelling"/>
                                            <div v-if="validationErrors[`spellings.${index}.spelling`]"
                                                 class="field-error">
                                                {{ validationErrors[`spellings.${index}.spelling`] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-item">
                                <label>{{ $t('term.filters.category') }}</label>
                                <select v-model="form.category" required>
                                    <option value="verb">{{ $t('term.category.verb') }}</option>
                                    <option value="noun">{{ $t('term.category.noun') }}</option>
                                    <option value="adjective">{{ $t('term.category.adjective') }}</option>
                                    <option value="numeral">{{ $t('term.category.numeral') }}</option>
                                    <option value="adverb">{{ $t('term.category.adverb') }}</option>
                                    <option value="preposition">{{ $t('term.category.preposition') }}</option>
                                    <option value="conjunction">{{ $t('term.category.conjunction') }}</option>
                                    <option value="determiner">{{ $t('term.category.determiner') }}</option>
                                    <option value="particle">{{ $t('term.category.particle') }}</option>
                                    <option value="phrase">{{ $t('term.category.phrase') }}</option>
                                    <option value="affix">{{ $t('term.category.affix') }}</option>
                                </select>
                                <div v-if="validationErrors[`category`]" class="field-error">{{
                                        validationErrors[`category`]
                                    }}
                                </div>
                            </div>
                            <div class="field-block">
                                <div class="field-block-head" @click="addAttribute('term')">
                                    <div>{{ $t('models.attributes') }}</div>
                                    <div class="field-item-add">+</div>
                                </div>
                                <div class="field-block-body" v-if="form.attributes.length > 0">
                                    <div class="field-set"
                                         v-for="(attribute, index) in form.attributes" :key="attribute.id">
                                        <img src="/img/trash.svg" alt="Delete" v-show="form.attributes.length > 0"
                                             @click="removeItem(index, form.attributes)"/>
                                        <div class="field-item">
<!--                                            todo: v-model="attribute.id"; this requires refactoring `handleAttributes`in the backend & looping through Gloss Attributes further down the same way-->
                                            <select v-model="attribute.attribute">
                                                <option v-for="attribute in editorData.attributes.filter(a => a.model === 'term')" :key="attribute.id"
                                                        :value="attribute.attribute">
                                                    {{ $t(`term.filters.attributes.${attribute.attribute}`) }}
                                                </option>
                                            </select>
                                            <div v-if="validationErrors[`attributes.${index}.attribute`]"
                                                 class="field-error">
                                                {{ validationErrors[`attributes.${index}.attribute`] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-block">
                        <div class="field-block-head" @click="addPronunciation()">
                            <div>{{ $t('models.pronunciations') }}</div>
                            <div class="field-item-add">+</div>
                        </div>
                        <div class="field-block-body" v-if="form.pronunciations.length > 0">
                            <div v-for="(pronunciation, index) in form.pronunciations" :key="index"
                                 class="field-set">
                                <img src="/img/trash.svg" alt="Delete" v-show="form.pronunciations.length > 1"
                                     @click="removeItem(index, form.pronunciations)"/>
                                <div class="field-item">
                                    <label>{{ $t('pronunciation.fields.transcription') }}</label>
                                    <input v-model="pronunciation.translit" required/>
                                    <div v-if="validationErrors[`pronunciations.${index}.translit`]"
                                         class="field-error">
                                        {{ validationErrors[`pronunciations.${index}.translit`] }}
                                    </div>
                                </div>
                                <div class="field-item">
                                    <label>{{ $t('pronunciation.fields.phonemic') }}</label>
                                    <input v-model="pronunciation.phonemic" required/>
                                    <div v-if="validationErrors[`pronunciations.${index}.phonemic`]"
                                         class="field-error">
                                        {{ validationErrors[`pronunciations.${index}.phonemic`] }}
                                    </div>
                                </div>
                                <div class="field-item">
                                    <label>{{ $t('pronunciation.fields.phonetic') }}</label>
                                    <input v-model="pronunciation.phonetic" required/>
                                    <div v-if="validationErrors[`pronunciations.${index}.phonetic`]"
                                         class="field-error">
                                        {{ validationErrors[`pronunciations.${index}.phonetic`] }}
                                    </div>
                                </div>
                                <div class="field-item">
                                    <label>{{ $t('pronunciation.fields.dialects') }}</label>
                                    <select v-model="pronunciation.dialect_id" required>
                                        <option v-for="dialect in editorData.dialects" :key="dialect.id"
                                                :value="dialect.id">
                                            {{ dialect.name }}
                                        </option>
                                    </select>
                                    <div v-if="validationErrors[`pronunciations.${index}.dialect_id`]"
                                         class="field-error">
                                        {{ validationErrors[`pronunciations.${index}.dialect_id`] }}
                                    </div>
                                </div>
                                <label class="checkbox">
                                    <span>{{ $t('pronunciation.fields.borrowed') }}</span>
                                    <input v-model="pronunciation.borrowed" type="checkbox" value=1/>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="field-block">
                        <div class="field-block-head">
                            <div>{{ $t('term.data.etymology') }}</div>
                        </div>
                        <div class="field-block-body"
                             v-if="!form.attributes.map(attr => attr.attribute).includes(['idiom', 'clitic'])">
                            <div class="field-item">
                                <label>{{ $t('actions.models.root') }}</label>
                                <input v-model="form.root.root"
                                       :required="form.category === 'verb' && !form.attributes.map(attr => attr.attribute).includes('pseudo')"/>
                                <div v-if="validationErrors[`root.root`]" class="field-error">
                                    {{ validationErrors[`root.root`] }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>{{ $t('term.data.type') }}</label>
                                <select v-model="form.etymology.type" required>
                                    <option value="inherited">{{ $t('term.data.inherited') }}</option>
                                    <option value="hybrid">{{ $t('term.data.hybrid') }}</option>
                                    <option value="borrowed">{{ $t('term.data.borrowed') }}</option>
                                </select>
                                <div v-if="validationErrors[`etymology.type`]" class="field-error">{{
                                        validationErrors[`etymology.type`]
                                    }}
                                </div>
                            </div>
                            <div class="field-item">
                                <label>{{ $t('term.data.source') }}</label>
                                <input v-model="form.etymology.source"/>
                            </div>

                            <div class="field-block"
                                 v-if="!form.attributes.some(attribute => attribute.attribute === 'idiom')">
                                <div class="field-block-head" @click="addPattern()">
                                    <div>{{ $t('models.patterns') }}</div>
                                    <div class="field-item-add">+</div>
                                </div>
                                <div class="field-block-body" v-if="form.patterns.length > 0">
                                    <div class="field-set" v-for="(pattern, index) in form.patterns" :key="index">
                                        <img src="/img/trash.svg" alt="Delete"
                                             v-show="form.category !== 'verb' && form.patterns.length > 0 || form.patterns.length > 1"
                                             @click="removeItem(index, form.patterns)"/>
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
                                                            <option value="CVCC">CVCC</option>
                                                            <option value="CVCCe">CVCCe</option>
                                                            <option value="CVCVC">CVCVC(e)</option>
                                                            <option value="CiCiC">CiCiC</option>
                                                        </optgroup>
                                                        <optgroup label="CCCC">
                                                            <option value="CVCCVC">CVCCVC(e)</option>
                                                            <option value="maCCVC">maCCVC(e)</option>
                                                        </optgroup>
                                                        <optgroup label="CCLC">
                                                            <option value="CCāC">CCāC</option>
                                                            <option value="CCāCe">CCāCe</option>
                                                            <option value="CCīC">CCīC(e)</option>
                                                            <option value="CCūC">CCūC(e)</option>
                                                        </optgroup>
                                                        <optgroup label="CCCLC">
                                                            <option value="CVCCLC">CVCCLC(e)</option>
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
                                                        <option value="CVCaC">CVCaC</option>
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
                                                        <option value="CVCCān">CVCCān</option>
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
                            <div>{{ $t('models.inflections') }}</div>
                            <div class="field-item-add">+</div>
                        </div>
                        <div class="field-block-body" v-if="form.inflections.length > 0">
                            <div v-for="(inflection, index) in form.inflections" :key="index"
                                 class="field-set">
                                <img src="/img/trash.svg" alt="Delete" v-show="form.inflections.length > 0"
                                     @click="removeItem(index, form.inflections)"/>
                                <div class="field-item">
                                    <label>{{ $t('inflection.fields.form') }}</label>
                                    <select v-model="inflection.form" required>
                                        <template v-if="form.category === 'noun'">
                                            <option
                                                v-if="form.attributes.map(attr => attr.attribute).includes('collective')"
                                                value="sing">singulative
                                            </option>
                                            <option
                                                v-if="form.attributes.map(attr => attr.attribute).includes('collective')"
                                                value="pauc">paucal
                                            </option>
                                            <option
                                                v-if="!form.attributes.map(attr => attr.attribute).includes('collective')"
                                                value="fem">feminine
                                            </option>
                                            <option value="plr">plural</option>
                                        </template>
                                        <option v-if="form.category === 'numeral'" value="cnst">construct</option>
                                        <template
                                            v-if="form.category === 'adjective' || form.category === 'numeral' || form.category === 'particle'">
                                            <option value="fem">feminine</option>
                                            <option value="plr">plural</option>
                                        </template>
                                        <option
                                            v-if="form.category === 'adjective' && form.attributes.length === 0"
                                            value="elt">
                                            elative
                                        </option>
                                        <option value="genitive">host (genitive)</option>
                                        <option value="accusative">host (accusative)</option>
                                        <option v-if="form.category === 'phrase'" value="resp">response</option>
                                    </select>
                                    <div v-if="validationErrors[`inflections.${index}.form`]" class="field-error">
                                        {{ validationErrors[`inflections.${index}.form`] }}
                                    </div>
                                </div>
                                <div class="field-item">
                                    <label>{{ $t('inflection.fields.inflection') }}</label>
                                    <input v-model="inflection.inflection" required/>
                                    <div v-if="validationErrors[`inflections.${index}.inflection`]" class="field-error">
                                        {{ validationErrors[`inflections.${index}.inflection`] }}
                                    </div>
                                </div>
                                <div class="field-item">
                                    <label>{{ $t('inflection.fields.transcription') }}</label>
                                    <input v-model="inflection.translit" required/>
                                    <div v-if="validationErrors[`inflections.${index}.translit`]" class="field-error">
                                        {{ validationErrors[`inflections.${index}.translit`] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field-block">
                        <div class="field-block-head">
                            <div>{{ $t('components.term.sections.info') }}</div>
                        </div>
                        <div class="field-block-body">
                            <div class="field-block">
                                <div class="field-block-head" @click="SearchStore.openSearchGenie('insert', 'terms')">
                                    <div>{{ $t('components.term.relatives.title') }}</div>
                                    <div class="field-item-add">+</div>
                                </div>
                                <div class="field-block-body" v-if="form.relatives.length > 0">
                                    <div class="field-set" v-for="(relative, index) in form.relatives" :key="index">
                                        <img src="/img/trash.svg" alt="Delete" v-show="form.relatives.length > 0"
                                             @click="removeItem(index, form.relatives)"/>
                                        <div class="field-item">
                                            <input :placeholder="relative.slug" disabled/>
                                            <div v-if="validationErrors[`relatives.${index}.slug`]" class="field-error">
                                                {{ validationErrors[`relatives.${index}.slug`] }}
                                            </div>
                                            <select v-model="relative.type"
                                                    @change="handleRelativeTypeChange(relative)">
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
                                            <div v-if="validationErrors[`relatives.${index}.type`]" class="field-error">
                                                {{ validationErrors[`relatives.${index}.type`] }}
                                            </div>

                                            <template v-if="glossRelativeTypes.includes(relative.type)">
                                                <select v-model="relative.gloss_id">
                                                    <option value=""></option>
                                                    <option v-for="(gloss, index) in form.glosses.filter(g => g.id)"
                                                            :key="index" :value="gloss.id">
                                                        {{ gloss.gloss }}
                                                    </option>
                                                </select>
                                                <div v-if="validationErrors[`relatives.${index}.gloss_id`]"
                                                     class="field-error">
                                                    {{ validationErrors[`relatives.${index}.gloss_id`] }}
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-item">
                                <label>{{ $t('term.data.image-url') }}</label>
                                <input v-model="form.image"/>
                            </div>
                            <div class="field-item">
                                <label>{{ $t('term.data.usage-notes') }}</label>
                                <textarea v-model="form.usage"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="field-block">
                        <div class="field-block-head" @click="addGloss()">
                            <div>{{ $t('models.glosses') }}</div>
                            <div class="field-item-add">+</div>
                        </div>
                        <div class="field-block-body" v-if="form.glosses.length > 0">
                            <div v-for="(gloss, index) in form.glosses" :key="index"
                                 class="field-set">
                                <img src="/img/trash.svg" alt="Delete" v-show="form.glosses.length > 1"
                                     @click="removeItem(index, form.glosses)"/>

                                <div class="field-item">
                                    <label>{{ $t('gloss.fields.gloss') }}</label>
                                    <input v-model="gloss.gloss" required/>
                                    <div v-if="validationErrors[`glosses.${index}.gloss`]" class="field-error">
                                        {{ validationErrors[`glosses.${index}.gloss`] }}
                                    </div>
                                </div>

                                <div class="field-block">
                                    <div class="field-block-head" @click="addAttribute('gloss', index)">
                                        <div>{{ $t('models.attributes') }}</div>
                                        <div class="field-item-add">+</div>
                                    </div>
                                    <div class="field-block-body" v-if="gloss.attributes.length > 0">
                                        <div class="field-set"
                                             v-for="(attribute, i) in gloss.attributes" :key="i">
                                            <img src="/img/trash.svg" alt="Delete"
                                                 v-show="gloss.attributes.length > 1 || (form.category !== 'verb' && gloss.attributes.length > 0)"
                                                 @click="removeItem(i, gloss.attributes)"/>
                                            <div class="field-item">
<!--                                                todo: loop over these & group them -->
                                                <select v-model="attribute.attribute">
                                                    <option value="auxiliary">auxiliary</option>
                                                    <option value="participle" v-if="form.category === 'adjective'">
                                                        participle
                                                    </option>
                                                    <template v-if="form.category === 'verb'">
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
                                                <div
                                                    v-if="validationErrors[`glosses.${index}.attributes.${i}.attribute`]"
                                                    class="field-error">
                                                    {{ validationErrors[`glosses.${index}.attributes.${i}.attribute`] }}
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
        </template>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="$t('modals.nav-guard.messages.unsaved-changes')"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>

<style scoped lang="scss">
.term-editor, .term-editor > * {
    width: 100%;
    max-width: 96rem;
    display: grid;
    gap: 3.2rem;
    align-content: start;
}

@media (width >= 960px) {
    .term-editor {
        grid-template-columns: repeat(2, 1fr);

        & > *:nth-child(3) {
            grid-column: span 2;
        }
    }
}
</style>
