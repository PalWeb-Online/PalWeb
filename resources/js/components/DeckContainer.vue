<script setup>
import {useDeck} from "../composables/Deck.js";
import DeckActions from "./DeckActions.vue";
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
import TermItem from "./TermItem.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    size: {type: String, default: 'm'},
});

const {data} = useDeck(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="deck-container">
            <div class="deck-container-head">
                <div class="deck-container-head-title">{{ data.deck.name }}</div>

                <PinButton modelType="deck" :model="data.deck"/>
                <PrivacyToggleButton modelType="deck" :model="data.deck"/>
                <DeckActions :model="data.deck"/>
            </div>

            <div class="user-wrapper">
                <div class="user-avatar">
                    <img :src="`/img/avatars/${data.deck.author.avatar}`" alt="Profile Picture"/>
                </div>
                <div class="user-comment">
                    <div class="user-comment-head">
                        <div>{{ data.deck.author.name }}</div>
                        <div>({{ data.deck.author.username }})</div>
                    </div>
                    <div class="user-comment-body">
                        <div class="user-comment-body-content">
                            <template v-if="data.deck.description">
                                {{ data.deck.description }}
                            </template>
                            <template v-else>
                                <i>Sadly, {{ data.deck.author.name }} hasn't told us anything about this Deck yet.</i>
                            </template>
                        </div>
                        <div class="user-comment-body-data">Created by {{ data.deck.author.name }}
                            on {{ data.deck.created_at }}.
                        </div>
                    </div>
                </div>
            </div>

            <template v-if="data.deck.terms.length > 0">
                <div class="terms-list">
                    <!--                todo: pass a Gloss -->
                    <TermItem v-for="term in data.deck.terms" :model="term"/>
                    <!--                <TermItem v-for="term in data.deck.terms" :model="term"-->
                    <!--                          :gloss="\App\Models\Gloss::find($term->pivot->gloss_id)"/>-->
                </div>
                <div class="deck-term-count">{{ data.deck.terms.length }} Terms</div>
            </template>
            <template v-else>
                <div class="tip">
                    <div class="material-symbols-rounded">info</div>
                    <div class="tip-content">
                        <p>This Deck is still empty! If this Deck is yours, use the menu in the top-right corner of this
                            page to
                            <a
                                href="{{ route('decks.edit', $deck->id) }}">Edit the Deck</a>, or hover over the Context
                            Actions
                            menu of a term & select the "Add to Deck" option to view a list of your Decks that you can
                            add
                            the
                            term
                            to.</p>
                    </div>
                </div>
            </template>
        </div>
    </template>
</template>
