<script>
import SearchBar from './SearchBar.vue';
import draggable from 'vuedraggable';

export default {
    components: {
        SearchBar,
        draggable,
    },

    props: [
        'mode',
        'id'
    ],

    computed: {
        updateRoute() {
            return `/dictionary/sentences/${this.id}`;
        },
        createRoute() {
            return `/dictionary/sentences`;
        },
    },
    data() {
        return {
            sentence: {
                sentence: '',
                translit: '',
                trans: '',
            },
            terms: [],
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            errors: null,
        }
    },
    created() {
        if (this.mode === "edit") {
            this.getSentence(this.id);
        }
    },
    methods: {
        getSentence(id) {
            axios.get("/dictionary/sentences/" + id + "/get")
                .then(response => {
                    this.sentence = response.data.sentence;
                    this.terms = response.data.terms;
                })
                .catch(error => {
                    this.errors = error.response.data['message']
                });
        },
        updatePosition() {
            this.terms.forEach((term, index) => {
                term.position = index + 1;
            });
        },
        addTerm() {
            this.terms.push({
                term: {
                    glosses: []
                },
                term_id: '',
                gloss_id: '',
                sent_term: '',
                sent_translit: '',
                position: '',
            });
            this.updatePosition();
        },
        removeTerm(index) {
            this.terms.splice(index, 1);
            this.updatePosition();
        },
        insertTerm(term) {
            this.terms.push({
                term: term.term,
                term_id: term.term.id,
                gloss_id: term.term.glosses[0].id,
                sent_term: term.term.term,
                sent_translit: term.term.translit,
                position: '',
            });
            this.updatePosition();
        },
        submit() {
            if (this.mode === "add") {
                axios.post(this.createRoute, {
                    sentence: this.sentence,
                    terms: this.terms
                })
                    .then(response => {
                        if (response.data.status === 'success') {
                            window.location = response.data.redirect;
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data['message']
                    });
            } else {
                axios.patch(this.updateRoute, {
                    sentence: this.sentence,
                    terms: this.terms
                })
                    .then(response => {
                        if (response.data.status === 'success') {
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

        <div class="auth-field">
            <label>Terms</label>
            <SearchBar :resultType="'model'" @emitTerm="insertTerm($event)"/>
            <div class="field-info">
                Start typing to search for Terms & add them to the Sentence.
            </div>
        </div>

        <div class="field-wrapper">
            <draggable :list="terms" @end="updatePosition()"
                       class="field-wrapper draggable" style="border: none; padding: 0">

                <template #item="{ element, index }">
                    <div class="form-field inline"
                         style="flex-flow: row wrap; justify-content: flex-start">

                        <div v-if="element.term_id">{{ element.term.term }} ({{ element.term.translit }})</div>

                        <input :id="'terms['+index+'][sent_term]'" :name="'terms['+index+'][sent_term]'"
                               type="text" v-model="element.sent_term"/>
                        <input :id="'terms['+index+'][sent_term]'" :name="'terms['+index+'][sent_term]'"
                               type="text" v-model="element.sent_translit"/>

                        <select v-if="element.term.glosses.length" v-model="element.gloss_id">
                            <option v-for="gloss in element.term.glosses" :value="gloss.id">{{gloss.gloss}}
                            </option>
                        </select>

                        <input style="display: none" :id="'terms['+index+'][term_id]'"
                               :name="'terms['+index+'][term_id]'"
                               type="text" v-model="element.term_id"/>

                        <img src="/img/trash.svg" alt="Delete" v-show="terms.length > 0" @click="removeTerm(index)"/>
                    </div>
                </template>
            </draggable>

            <div class="field-add" @click="addTerm()">+ Add TERM</div>
        </div>

        <div class="auth-field">
            <label for="sentence[trans]">Translation</label>
            <div class="field-input">
                <input id="sentence[trans]" v-model="sentence.trans" name="sentence[trans]" required type="text"/>
            </div>
            <div class="field-info">
                Write the translation of the Sentence in English.
            </div>
        </div>

        <button type="submit" class="sp-button">
            <template v-if="mode === 'add'">Create</template>
            <template v-if="mode === 'edit'">Update</template>
        </button>
    </form>
</template>
