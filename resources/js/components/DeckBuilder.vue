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
        'deckId'
    ],

    computed: {
        updateRoute() {
            return `/community/decks/${this.deckId}`;
        },
        createRoute() {
            return `/community/decks`;
        },
        privateBoolean: {
            get() {
                return !!this.deck.private;
            },
            set(value) {
                this.deck.private = value ? 1 : 0;
            }
        }
    },
    data() {
        return {
            deck: {
                name: '',
                description: '',
                private: false,
            },
            terms: [],
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            errors: null,
        }
    },
    created() {
        this.addTerm();
        if (this.mode === "edit") {
            this.getDeck(this.deckId);
        }
    },
    methods: {
        getDeck(deckId) {
            axios.get("/community/decks/" + deckId + "/get")
                .then(response => {
                    this.deck = response.data.deck;
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
                slug: '',
                position: '',
            });
            this.updatePosition();
        },
        removeTerm(index) {
            this.terms.splice(index, 1);
            this.updatePosition();

        },
        insertSlug(index, slug) {
            this.terms[index].slug = slug;
        },
        submit() {
            if (this.mode === "add") {
                axios.post(this.createRoute, {
                    deck: this.deck,
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
                    deck: this.deck,
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
            <label for="deck[name]">Name</label>
            <div class="field-input">
                <input id="deck[name]" v-model="deck.name" name="deck[name]" required type="text"/>
            </div>
            <div class="field-info">
                What would you like to call your Deck?
            </div>
        </div>
        <div class="auth-field">
            <label for="deck[description]">Description</label>
            <div class="field-input">
                <textarea id="deck[description]" v-model="deck.description"
                          name="deck[description]"/>
            </div>
            <div class="field-info">
                What's your Deck about?
            </div>
        </div>

        <div class="field-wrapper">
            <draggable :list="terms" @end="updatePosition()"
                       class="field-wrapper draggable" style="border: none; padding: 0">
                <template #item="{ element, index }">
                    <div class="form-field inline"
                         style="flex-flow: row wrap; row-gap: 0; justify-content: flex-start">
                        <label :for="'terms['+index+'][slug]'"
                               style="margin-inline-end: 0; flex-basis: auto">{{ index + 1 }}.</label>
                        <SearchBar :resultType="'slug'" @emitSlug="insertSlug(index, $event)"/>
                        <input :id="'terms['+index+'][slug]'" :name="'terms['+index+'][slug]'" type="text"
                               v-model="element.slug"/>

                        <img src="/img/trash.svg" alt="Delete" v-show="terms.length > 0" @click="removeTerm(index)"/>
                    </div>
                </template>
            </draggable>

            <div class="field-add" @click="addTerm()">+ Add TERM</div>
        </div>

        <label class="checkbox" for="deck[private]">
            <input type="checkbox" value=1 id="deck[private]" name="deck[private]" v-model="privateBoolean">
            <span>Private</span>
        </label>

        <button type="submit" class="sp-button">
            <template v-if="mode === 'add'">Create</template>
            <template v-if="mode === 'edit'">Update</template>
        </button>
    </form>
</template>
