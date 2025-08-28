<script setup>
import {route} from "ziggy-js";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/Actions/DeckActions.vue";
import {useQuizzerStore} from "../Stores/QuizzerStore.js";

const QuizzerStore = useQuizzerStore();
</script>
<template>
    <div class="quizzer-title featured-title l">Quizzer</div>
    <div id="quizzer-container" class="window-container">
        <div class="window-header">
            <template v-if="QuizzerStore.data.step !== 'select'">
                <Link v-if="QuizzerStore.data.step === 'setup'" :href="route('quizzer.index')"
                      class="material-symbols-rounded">close
                </Link>
                <Link v-else :href="route('quizzer.deck', QuizzerStore.data.model.id)" class="material-symbols-rounded">
                    arrow_back
                </Link>
                <div class="window-header-url">www.palweb.app/academy/quizzer/{{ QuizzerStore.data.quizType }}/{deck}
                </div>
            </template>
            <template v-else>
                <div class="material-symbols-rounded">home</div>
                <div class="window-header-url">www.palweb.app/academy/quizzer</div>
            </template>
        </div>
        <div class="window-section-head">
            <h1>Deck</h1>
            <template v-if="QuizzerStore.data.model">
                <PinButton modelType="deck" :model="QuizzerStore.data.model"/>
                <DeckActions :model="QuizzerStore.data.model"/>
            </template>
        </div>
        <div class="window-content-head">
            <div class="window-content-head-title">{{ QuizzerStore.data.model?.name }}</div>
        </div>
        <slot/>
    </div>
</template>
