<script>
import SearchBar from './SearchBar.vue';

export default {
    components: {
        SearchBar,
    },

    data() {
        return {
            selectedCategory: new URLSearchParams(window.location.search).get('category') || '',
            selectedAttribute: new URLSearchParams(window.location.search).get('attribute') || '',
            selectedForm: new URLSearchParams(window.location.search).get('form') || '',
            selectedSingular: new URLSearchParams(window.location.search).get('singular') || '',
            selectedPlural: new URLSearchParams(window.location.search).get('plural') || '',
        };
    },

    computed: {
        hasAttribute() {
            const allowedCategories = ['', 'verb', 'noun', 'adjective', 'determiner'];
            return allowedCategories.includes(this.selectedCategory);
        },
        hasForm() {
            const allowedCategories = ['', 'verb', 'noun', 'adjective', 'numeral'];
            const allowedSingPatterns = ['', 'ap', 'pp', 'nv'];
            const disallowedAttributes = ['pseudo', 'defect']; // Add more as needed
            return allowedCategories.includes(this.selectedCategory) && allowedSingPatterns.includes(this.selectedSingular) && !disallowedAttributes.includes(this.selectedAttribute);
        },
        hasSingular() {
            const allowedCategories = ['', 'noun', 'adjective', 'numeral'];
            return allowedCategories.includes(this.selectedCategory);
        },
        hasPlural() {
            const allowedCategories = ['', 'noun', 'adjective'];
            const disallowedAttributes = ['collective', 'demonym', 'defect'];
            return allowedCategories.includes(this.selectedCategory) && !disallowedAttributes.includes(this.selectedAttribute);
        },
        isRegular() {
            const allowedSingPatterns = ['CiCiC', 'CCīC', 'relative', 'ia'];
            return allowedSingPatterns.includes(this.selectedSingular);
        },
        isCCC() {
            const allowedSingPatterns = ['CLC', 'CvCC', 'CvCCe', 'CvCvC'];
            return allowedSingPatterns.includes(this.selectedSingular);
        }
    },
    methods: {
        resetFilters() {
            this.selectedCategory = '';
            this.selectedAttribute = '';
            this.selectedForm = '';
            this.selectedSingular = '';
            this.selectedPlural = '';
        },
        submitForm() {
            this.$nextTick(() => {
                document.getElementById('dictionary_search').submit();
            });
        },
        onCategoryChange() {
            this.selectedAttribute = '';
            this.selectedForm = '';
            this.selectedSingular = '';
            this.selectedPlural = '';
        }
    }
};
</script>

<template>
    <form id="dictionary_search" class="search-tools" method="GET">
        <SearchBar/>

        <div class="dictionary-filters">
            <div class="filter-container">
                <div class="filter-name">category</div>
                <select v-model="selectedCategory" @change="onCategoryChange">
                    <option value=""></option>
                    <option value="verb">Verbs</option>
                    <option value="noun">Nouns</option>
                    <option value="adjective">Adjectives</option>
                    <option value="numeral">Numerals</option>
                    <option value="adverb">Adverbs</option>
                    <option value="preposition">Prepositions</option>
                    <option value="conjunction">Conjunctions</option>
                    <option value="determiner">Determiners</option>
                    <option value="particle">Particles</option>
                    <option value="phrase">Phrases</option>
                    <option value="affix">Affixes</option>
                </select>
            </div>
            <div v-if="hasAttribute" class="filter-container">
                <div class="filter-name">attribute</div>
                <select v-model="selectedAttribute">
                    <option value=""></option>
                    <option value="idiom">Idiom</option>
                    <option value="clitic">Clitic</option>
                    <option v-if="(selectedCategory === '' || selectedCategory === 'verb') && selectedForm === ''"
                            value="pseudo">
                        Pseudo V.
                    </option>
                    <option
                        v-if="selectedCategory === '' || selectedCategory === 'noun' || selectedCategory === 'determiner'"
                        value="masculine">
                        Masculine
                    </option>
                    <option
                        v-if="selectedCategory === '' || selectedCategory === 'noun' || selectedCategory === 'determiner'"
                        value="feminine">
                        Feminine
                    </option>
                    <option
                        v-if="selectedCategory === '' || selectedCategory === 'noun' || selectedCategory === 'determiner'"
                        value="plural">
                        Plural
                    </option>
                    <option v-if="selectedCategory === '' || selectedCategory === 'noun'" value="collective">
                        Collective
                        N.
                    </option>
                    <option
                        v-if="selectedCategory === '' || selectedCategory === 'noun' || selectedCategory === 'adjective'"
                        value="demonym">
                        Demonym
                    </option>
                    <option
                        v-if="(selectedCategory === '' || selectedCategory === 'adjective') && selectedForm === ''"
                        value="defect">
                        Defect Adj.
                    </option>
                    <option v-if="selectedCategory === '' || selectedCategory === 'adjective'" value="participle">
                        Active
                        Part.
                    </option>
                    <option value="quantifier">Quantifier</option>
                    <option value="complementizer">Complementizer</option>
                </select>
            </div>
            <div v-if="hasForm" class="filter-container">
                <div class="filter-name">form</div>
                <select v-model="selectedForm">
                    <option value=""></option>
                    <option value="1">Form 1</option>
                    <template v-if="selectedCategory !== 'numeral' && selectedPlural !== 'Cu22āC'">
                        <option value="2">Form 2</option>
                        <option value="3">Form 3</option>
                        <option value="4">Form 4</option>
                        <option value="5">Form 5</option>
                        <option value="6">Form 6</option>
                        <option value="7">Form 7</option>
                        <option value="8">Form 8</option>
                        <option value="9">Form 9</option>
                        <option value="X">Form X</option>
                        <option value="2Q">Form 2Q</option>
                        <option value="5Q">Form 5Q</option>
                    </template>
                </select>
            </div>
            <div v-if="hasSingular" class="filter-container">
                <div class="filter-name">singular</div>
                <select v-model="selectedSingular">
                    <option value=""></option>
                    <optgroup v-if="selectedCategory === 'numeral'" label="Derived Terms">
                        <option value="ap">Act. Part.</option>
                    </optgroup>
                    <template v-if="selectedCategory !== 'numeral'">
                        <optgroup label="Derived Terms">
                            <option value="ap">Act. Part.</option>
                            <option value="pp">Pas. Part.</option>
                            <option value="nv">Verbal Noun</option>
                        </optgroup>
                        <optgroup label="Named Patterns">
                            <option value="relative">Relative Adj.</option>
                            <option value="ia">Intensive Adj.</option>
                            <option value="na">Nominalized Adj.</option>
                        </optgroup>
                        <optgroup label="CCC">
                            <option value="CLC">CLC(e)</option>
                            <option value="CvCC">CvCC</option>
                            <option value="CvCCe">CvCCe</option>
                            <option value="CvCvC">CvCvC(e)</option>
                            <option value="CiCiC">CiCiC</option>
                        </optgroup>
                        <optgroup label="CCLC">
                            <option value="CCāC">CCāC</option>
                            <option value="CCāCe">CCāCe</option>
                            <option value="CCīC">CCīC(e)</option>
                            <option value="CCūC">CCūC(e)</option>
                        </optgroup>
                        <optgroup label="CCCC">
                            <option value="CvCCvC">CvCCvC(e)</option>
                            <option value="maCCvC">maCCvC(e)</option>
                        </optgroup>
                        <optgroup label="CCCLC">
                            <option value="CvCCLC">CvCCLC(e)</option>
                            <option value="Ca22āC">Ca22āC(e)</option>
                            <option value="Ca22īC">Ca22īC</option>
                            <option value="Ca22ūC">Ca22ūC(e)</option>
                        </optgroup>
                    </template>
                </select>
            </div>
            <div v-if="hasPlural" class="filter-container">
                <div class="filter-name">plural</div>
                <select v-model="selectedPlural">
                    <option value=""></option>
                    <optgroup v-if="selectedSingular === '' || isRegular" label="sound">
                        <option value="-īn">-īn</option>
                        <option value="-āt">-āt</option>
                    </optgroup>
                    <optgroup label="CCC">
                        <template v-if="selectedSingular === '' || isCCC">
                            <option value="CCāC">CCāC</option>
                            <option value="CCūC">CCūC</option>
                            <option value="ʔaCCāC">ʔaCCāC</option>
                            <option v-if="selectedSingular === 'CvCCe'" value="CvCaC">CvCaC</option>
                        </template>
                    </optgroup>
                    <optgroup
                        v-if="selectedSingular === '' || (selectedSingular === 'CCāC' || selectedSingular === 'CCīC')"
                        label="CCLC">
                        <template v-if="selectedSingular !== 'CCīC'">
                            <option value="ʔaCCiCe">ʔaCCiCe</option>
                            <option value="CCīC">CCīC</option>
                        </template>
                        <template v-if="selectedSingular !== 'CCāC'">
                            <option value="CCāC">CCāC</option>
                            <option value="CuCaCa">CuCaCa</option>
                        </template>
                        <option value="CuCuC">CuCuC</option>
                    </optgroup>
                    <optgroup v-if="!isRegular" label="CCCC">
                        <option value="CaCāCiC">CaCāCiC</option>
                    </optgroup>
                    <optgroup
                        v-if="selectedSingular === '' || selectedSingular === 'CvCCLC' || selectedSingular === 'relative'"
                        label="CCCLC">
                        <option v-if="selectedSingular !== 'relative'" value="CaCāCīC">CaCāCīC</option>
                        <option value="CaCāCCe">CaCāCCe</option>
                    </optgroup>
                    <optgroup v-if="selectedSingular === '' || selectedSingular === 'ap'" label="CLCC">
                        <option value="Cu22āC">Cu22āC</option>
                        <option value="CCāC">CCāC</option>
                    </optgroup>
                    <optgroup v-if="!isRegular" label="Other">
                        <option value="CvCCān">CvCCān</option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="search-buttons">
            <button type="button" @click="resetFilters">reset</button>
            <button type="button" @click="submitForm">apply</button>
        </div>

        <!-- Hidden Inputs -->
        <input :value="selectedCategory" name="category" type="hidden">
        <input :value="selectedAttribute" name="attribute" type="hidden">
        <input :value="selectedForm" name="form" type="hidden">
        <input :value="selectedSingular" name="singular" type="hidden">
        <input :value="selectedPlural" name="plural" type="hidden">

    </form>
</template>

<!--        <div class="filter-info" v-if="selectedAttribute === 'collective'">-->
<!--            <h1>Collective Nouns</h1>-->
<!--            <p><a href="/docs/nouns#collective" target="_blank">Read more about Collective Nouns.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedAttribute === 'demonym'">-->
<!--            <h1>Demonyms</h1>-->
<!--            <p><a href="/docs/nouns#demonym" target="_blank">Read more about Demonyms.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'ap'">-->
<!--            <h1>Active Participles</h1>-->
<!--            <p><b>Active Participles</b> conform to the <b>10 Verb Forms</b>, regardless of whether the term-->
<!--                they-->
<!--                are "derived from" is attested or not. <b>APs</b> have the pattern <b>fāʕil</b> (e.g.-->
<!--                <b>كاتب</b>)-->
<!--                when they're derived from <b>Form 1</b>; otherwise they are formed by attaching <b>m-</b> to the-->
<!--                <b>Present Stem</b> of any other <b>Form</b> (e.g. <b>معلّم</b>).</p>-->
<!--            <p><b>Active Participles</b> — whether or not they are used as participles (e.g. <b>كاتب "has-->
<!--                written"</b> vs. <b>شاطر "smart"</b>) — are adjectives by default & always have <b>sound-->
<!--                plurals</b>. However,-->
<!--                <b>Form 1 Active Participles</b> that have been lexicalized into nouns have the broken plural-->
<!--                <b>fuʕʕāl</b> (e.g <b>كتّاب-->
<!--                    "writers"</b>); note that this may result in the term seemingly having two plural forms.</p>-->
<!--            <p><a href="/docs/adjectives#ap" target="_blank">Read more about Active Participles.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'pp'">-->
<!--            <h1>Passive Participles</h1>-->
<!--            <p><b>Passive Participles</b> conform to the <b>10 Verb Forms</b>, regardless of whether the term-->
<!--                they-->
<!--                are "derived from" is attested or not. <b>PPs</b> have the pattern <b>mafʕūl</b> (e.g.-->
<!--                <b>مكتوب</b>)-->
<!--                when they are derived from <b>Form 1</b>; otherwise they are formed by attaching <b>m-</b> to-->
<!--                the-->
<!--                <b>Past Stem</b> of any other <b>Form</b> (e.g. <b>مرتّب</b>).</p>-->
<!--            <p><b>Passive Participles</b> are adjectives by default & always have-->
<!--                <b>sound plurals</b> (e.g. <b>مشهور -> مشهورين "famous"</b>). However, <b>Form 1 Passive-->
<!--                    Participles</b> that have been lexicalized into nouns — having the pattern <b>CaCCLC</b> —-->
<!--                have the broken plural <b>CaCāCīC</b> (e.g <b>مشهور -> مشاهير-->
<!--                    "celebrities"</b>); note that this may result in the term seemingly having two plural forms.-->
<!--            </p>-->
<!--            <p><a href="">Read more about Passive Participles.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'nv'">-->
<!--            <p>You've filtered down your search to terms that have the form of <b>Nominalized Verbs</b>.</p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'ia'">-->
<!--            <p>You've filtered down your search to terms that have the form of <b>Intensive Adjectives</b>.</p>-->
<!--            <p><b>Intensive Adjectives</b> have the pattern <b>faʕlān</b> (e.g. <b>تعبان</b>) & always have <b>sound-->
<!--                plurals</b>; they are usually associated with a <b>stative verb</b> (e.g. <b>تعب</b>), which-->
<!--                in turn is usually in <b>Form A2</b>. Accordingly, they are adjectives that refer to the current-->
<!--                state of an animate noun; they are analogous to <b>Active Participles</b>, but refer to a state-->
<!--                rather than an action.</p>-->
<!--            <p><a href="">Read more about Intensive Adjectives.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'relative'">-->
<!--            <p>You've filtered down your search to terms that have the form of <b>Relative Adjectives</b> &-->
<!--                <b>Nominalized Adjectives</b>.</p>-->
<!--            <p><b>Relative Adjectives</b> are formed by attaching the suffix <b>-iyy</b> to a noun (e.g.-->
<!--                <b>مسيحيّ</b>)-->
<!--                & always have <b>sound plurals</b>.</p>-->
<!--            <p><b>Nominalized Adjectives</b> are nouns formed from <b>Relative Adjectives</b> by attaching the-->
<!--                suffix <b>-e</b> (e.g. <b>مسيحيّة</b>). Since they refer to an abstraction of the <b>Relative-->
<!--                    Adjective</b>, they generally have no plural; if they do, it is a <b>sound plural</b>.</p>-->
<!--            <p><a href="">Read more about Relative Adjectives.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'relative'">-->
<!--            <p>You've filtered down your search to terms that have the form of <b>Defect Adjectives</b>.</p>-->
<!--            <p><b>Defect Adjectives</b> have the pattern <b>ʔafʕal</b> (e.g. <b>أحمر</b>).</p>-->
<!--            <p><a href="">Read more about Defect Adjectives.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'relative'">-->
<!--            <p>You've filtered down your search to terms that have the form of <b>Agent Nouns</b>.</p>-->
<!--            <p><b>Agent Nouns</b> have the pattern <b>faʕʕāl</b> (e.g. <b>شغّال</b>) & always have <b>sound-->
<!--                plurals</b>.</p>-->
<!--            <p><a href="">Read more about Agent Nouns.</a></p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'relative'">-->
<!--            <p>You've filtered down your search to terms in the <b>maCCvC</b> pattern.</p>-->
<!--            <p>This is a subset of terms under the broader <b>CvCCvC</b> pattern. It is often referred to as the-->
<!--                <b>location noun</b> pattern, because terms in this pattern often refer to locations; however,-->
<!--                this generalization does not always apply. Note that the second vowel is either <b>/a/</b> or-->
<!--                <b>/i/</b>; some terms in this pattern end in <b>/-e/</b> as well. Echoing other <b>CvCCvC</b>-->
<!--                terms, the plural form is usually <b>maCāCiC</b>; terms ending in <b>/-e/</b> may have <b>sound-->
<!--                    plurals</b> as well.</p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'relative'">-->
<!--            <p>You've filtered down your search to terms in the <b>CiCāCe</b> pattern.</p>-->
<!--            <p>In <b>Standard Arabic</b>, this is considered a <b>Form 1 Verbal Noun</b> pattern. Generally,-->
<!--                terms in this pattern are <b>learned borrowings</b> & have <b>sound plurals</b>.</p>-->
<!--        </div>-->
<!--        <div class="filter-info" v-if="selectedSingular === 'relative'">-->
<!--            <p>You've filtered down your search to terms in the <b>CvCCe</b> pattern.</p>-->
<!--            <p>Generally, terms in this pattern have <b>broken plurals</b> in the <b>CvCaC</b> pattern — or they-->
<!--                may have <b>sound plurals</b>. Note that the initial vowel is either <b>/i/</b> or <b>/u/</b>;-->
<!--                it is the same in the singular & plural form.</p>-->
<!--        </div>-->
