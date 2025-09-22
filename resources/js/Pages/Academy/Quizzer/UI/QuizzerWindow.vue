<script setup>
import {route} from "ziggy-js";
import PinButton from "../../../../components/PinButton.vue";
import DeckActions from "../../../../components/Actions/DeckActions.vue";
import {useQuizzerStore} from "../Stores/QuizzerStore.js";

const QuizzerStore = useQuizzerStore();
</script>
<template>
    <div id="quizzer-container" class="window-container">
        <div class="window-header">
            <template v-if="QuizzerStore.data.step !== 'select'">
                <Link v-if="QuizzerStore.data.step === 'settings'" :href="route('quizzer.index')"
                      class="material-symbols-rounded">close
                </Link>
                <Link v-else :href="route('quizzer.show', { scorable_type: QuizzerStore.data.scorable_type, scorable_id:  QuizzerStore.data.model.id })" class="material-symbols-rounded">
                    arrow_back
                </Link>
                <div class="window-header-url">www.palweb.app/academy/quizzer/{{ QuizzerStore.data.scorable_type }}/{{'{'+QuizzerStore.data.scorable_type+'}'}}
                </div>
            </template>
            <template v-else>
                <div class="material-symbols-rounded">home</div>
                <div class="window-header-url">www.palweb.app/academy/quizzer</div>
            </template>
        </div>
        <div class="window-section-head">
            <h1>{{ QuizzerStore.data.scorable_type }}</h1>
            <template v-if="QuizzerStore.data.model">
                <PinButton :modelType="QuizzerStore.data.scorable_type" :model="QuizzerStore.data.model"/>
                <DeckActions v-if="QuizzerStore.data.scorable_type === 'deck'" :model="QuizzerStore.data.model"/>
            </template>
        </div>
        <div class="window-content-head">
            <div class="window-content-head-title">{{ QuizzerStore.data.scorable_type === 'deck' ? QuizzerStore.data.model?.name : QuizzerStore.data.model?.title }}</div>
        </div>
        <slot/>
    </div>
</template>
